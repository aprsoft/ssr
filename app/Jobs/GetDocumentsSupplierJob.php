<?php

namespace App\Jobs;

use App\Models\Detail;
use App\Models\Document;
use App\Models\Supplier;
use App\Models\Typedocument;
use App\Models\ErrorLog;
use App\Models\Payment;
use App\Models\Tenant;
use App\Services\Dte\Get\DteGetManager;
use App\Services\Dte\DteSupplierMapper;
use App\Services\Dte\DteSupplierValidator;
use App\Services\Dte\DteTypeResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetDocumentsSupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $pagina;
    protected string $fecha;
    protected Tenant $tenant;

    public function __construct(Tenant $tenant, int $pagina, string $fecha)
    {
        $this->tenant = $tenant;
        $this->pagina = $pagina;
        $this->fecha  = $fecha;
    }

    public function handle(): void
    {
        tenancy()->initialize($this->tenant);

        Log::info('DESPUES initialize()', [
                'tenant_id'   => tenant()->id,
                'initialized' => tenancy()->initialized,
                'database'    => DB::connection()->getDatabaseName(),
            ]);

        Log::info('GetDocumentsSupplierJob iniciado', [
            'pagina' => $this->pagina,
            'fecha'  => $this->fecha,
        ]);

        $formato    = 'json';
        $dteManager = new DteGetManager();

        $response = $dteManager->getDocumentsSupplier($this->pagina, $this->fecha);

        if (!$response || !$response->accepted) {
            ErrorLog::create([
                'message' => $response->message ?? 'Error desconocido al obtener documentos de proveedores',
                'errors'  => json_encode($response),
            ]);
            return;
        }

        foreach ($response->data as $doc) {

            $document  = null;
            $rutEmisor = null;

            try {
                $rutEmisor = $doc['RUTEmisor'] . '-' . $doc['DV'];

                $typedocument = Typedocument::where('codigo', $doc['TipoDTE'])->first();

                $yaExiste = Document::where('folio', $doc['Folio'])
                    ->where('typedocument_id', optional($typedocument)->id)
                    ->where('estado_documento', 'VIGENTE')
                    ->whereNotNull('supplier_id')
                    ->exists();

                if ($yaExiste) {
                    Log::info('Documento proveedor ya existe', [
                        'folio' => $doc['Folio'],
                        'tipo'  => $doc['TipoDTE'],
                        'rut'   => $rutEmisor,
                    ]);
                    continue;
                }

                // =========================
                // OBTENER DETALLE DTE
                // =========================
                $detalleDoc = $dteManager->getDetailsSupplier(
                    $rutEmisor,
                    $doc['TipoDTE'],
                    $doc['Folio'],
                    $formato
                );

                       

                if (!$detalleDoc || !$detalleDoc->accepted) {
                    throw new \Exception('No se pudo obtener detalle del DTE');
                }

                // =========================
                // VALIDACIÓN Y MAPEO
                // =========================
                DteSupplierValidator::validate($detalleDoc->data);
                $mapped = DteSupplierMapper::map($detalleDoc->data);

                $detalleVacio = empty($mapped['detalle']);

                // =========================
                // TRANSACCIÓN
                // =========================
                DB::transaction(function () use (
                    $mapped,
                    $doc,
                    $detalleVacio,
                    &$document
                ) {

                    // =========================
                    // SUPPLIER
                    // =========================
                    $supplier = Supplier::firstOrCreate(
                        ['rut' => $mapped['emisor']['rut']],
                        [
                            'nombres'             => $mapped['emisor']['razon'],
                            'giro_proveedor'      => $mapped['emisor']['giro'],
                            'direccion_proveedor' => $mapped['emisor']['direccion'],
                            'telefono_fijo'       => $mapped['emisor']['telefono'],
                            'comuna'              => $mapped['emisor']['comuna'],
                            'ciudad'              => $mapped['emisor']['ciudad'],
                            'email_proveedor'     => $mapped['emisor']['email'],
                            'Acteco'              => json_encode($mapped['emisor']['acteco']),
                            'condicion_proveedor' => 'EMPRESA',
                        ]
                    );

                    // =========================
                    // DOCUMENT
                    // =========================
                    $typedocument = Typedocument::where(
                        'codigo',
                        $mapped['documento']['tipo_dte']
                    )->first();

                    $document = Document::create([
                        'supplier_id'     => $supplier->id,
                        'typedocument_id' => optional($typedocument)->id,
                        'folio'           => $mapped['documento']['folio'],
                        'fecha'           => $mapped['documento']['fecha'],
                        'fmaPago' => match ($mapped['documento']['fma_pago']) {
                            1 => 'CONTADO',
                            2 => 'CRÉDITO',
                            3 => 'SIN COSTO',
                            default => 'NO INFORMADO',
                        },
                        'monto_exento' => DteTypeResolver::isExento($mapped['documento']['tipo_dte'])
                            ? $mapped['totales']['total']
                            : null,
                        'monto_neto'  => $mapped['totales']['neto'],
                        'monto_iva'   => $mapped['totales']['iva'],
                        'monto_total' => $mapped['totales']['total'],
                        'Referencia' => $mapped['referencia']
                            ? json_encode($mapped['referencia'])
                            : null,
                        'acuses' => json_encode($doc['Acuses'] ?? []),
                        'estado_documento'    => 'VIGENTE',
                        'condicion_documento' => 'CONTADO',
                    ]);
                 

                    // =========================
                    // PAYMENT
                    // =========================
                    $payment = Payment::create([
                        'typedocumentpayment_id' => 1,
                        'supplier_id'            => $supplier->id,
                        'nombre_completo'        => $supplier->fullName,
                        'estado_pago'            => 'VIGENTE',
                        'tipo_movimiento'        => 'EGRESO',
                        'forma_pago'             => 'SIN INFORMACION',
                        'monto'                  => $mapped['totales']['total'],
                        'fecha'                  => $mapped['documento']['fecha'],
                    ]);

                    $document->payment()->attach($payment, [
                        'estado_abono' => 'VIGENTE',
                        'abono'        => $document->total,
                    ]);

                    // =========================
                    // DETALLES
                    // =========================
                    if ($detalleVacio) {
                        Detail::create([
                            'document_id'    => $document->id,
                            'estado_detalle' => 'VIGENTE',
                            'cantidad'       => 1,
                            'valor'          => $document->neto,
                            'neto'           => $document->neto,
                            'iva'            => $document->iva,
                            'total'          => $document->total,
                            'detalle'        => 'SIN INFORMACION DEL PROVEEDOR',
                        ]);

                        return;
                    }

                    foreach ($mapped['detalle'] ?? [] as $d) {

                        $cantidad = (int) ($d['QtyItem'] ?? 1);
                        $valor    = (float) ($d['PrcItem'] ?? $d['MontoItem']);

                        // Neto del ítem
                        $neto = round($valor * $cantidad);

                        $esExento = DteTypeResolver::isExento($mapped['documento']['tipo_dte']);

                        $iva = $esExento
                            ? 0
                            : round($neto * 0.19);

                        $total = $esExento
                            ? $neto
                            : $neto + $iva;

                        Detail::create([
                            'document_id'    => $document->id,
                            'estado_detalle' => 'VIGENTE',
                            'cantidad'       => $cantidad,
                            'valor'          => round($valor),
                            'neto'           => $neto,
                            'iva'            => $iva,
                            'total'          => $total,
                            'detalle'        => $d['NmbItem'],
                        ]);
                    }

                });

                Log::info('Documento proveedor registrado correctamente', [
                    'folio' => $doc['Folio'],
                    'tipo'  => $doc['TipoDTE'],
                    'rut'   => $rutEmisor,
                ]);

            } catch (\Throwable $e) {

                Log::error('Error procesando documento', [
                    'document_id' => $document?->id,
                    'folio'       => $doc['Folio'] ?? null,
                    'tipo_dte'    => $doc['TipoDTE'] ?? null,
                    'rut_emisor'  => $rutEmisor,
                    'exception'   => $e,
                ]);

                ErrorLog::create([
                    'document_id' => $document?->id,
                    'folio'       => $doc['Folio'] ?? null,
                    'tipo_dte'    => $doc['TipoDTE'] ?? null,
                    'rut_emisor'  => $rutEmisor,
                    'message'     => $e->getMessage(),
                    'exception'   => get_class($e),
                    'file'        => $e->getFile(),
                    'line'        => $e->getLine(),
                ]);

                continue;
            }
        }

        tenancy()->end();
    }
}
