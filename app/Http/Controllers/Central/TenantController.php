<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Central\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\ValidationException;
// use App\Events\NewTenantUserCreated;
// use App\Models\CentralErrorLog;
// use Stancl\Tenancy\Database\Models\Domain;
// use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return view('central.tenants.index');
    }

    public function create()
    {
        return view('central.tenants.create');
    }

    public function store()
    {
        $tenant = null;

        try {
            $data = request()->all();

            // Mostrar debug de lo que llega
            logger()->info('Datos recibidos en store(): ', $data);

            // Validación básica
            $requestValidate = [
                'id' => 'required|alpha_dash', // temporalmente sin unique
            ];

            $validator = Validator::make($data, $requestValidate);

            if ($validator->fails()) {
                // Mostrar errores claros
                logger()->warning('Errores de validación: ', $validator->errors()->toArray());
                return back()
                    ->withErrors($validator)
                    ->with('error', 'Error de validación. Revisa los datos.');
            }

            // Comprobar duplicado manualmente
            if (\App\Models\Tenant::where('id', $data['id'])->exists()) {
                return back()->with('error', "El Tenant con ID '{$data['id']}' ya existe.");
            }

            // Crear tenant
            $tenant = Tenant::create([
                'id' => $data['id'],
            ]);

            logger()->info('Tenant creado: ', ['tenant_id' => $tenant->id]);

            // Crear dominio asociado dentro de transacción
            $centralDomains = config('tenancy.central_domains');
            if (empty($centralDomains) || !is_array($centralDomains)) {
                throw new \Exception('No hay dominios centrales configurados.');
            }
            $baseDomain = $centralDomains[0];

            DB::transaction(function() use ($tenant, $baseDomain, $data) {
                $tenant->domains()->create([
                    'domain' => $data['id'] . '.' . $baseDomain,
                ]);
            });

            // Dispatch event
            // \App\Events\NewTenantUserCreated::dispatch($tenant);

            return redirect()
                ->route('central.dashboard')
                ->with('success', "Tenant '{$data['id']}' creado correctamente.");

        } catch (\Exception $e) {
            // Si algo falla, eliminar tenant creado para evitar inconsistencias
            if ($tenant) {
                try {
                    $tenant->delete();
                } catch (\Exception $ex) {
                    logger()->error('Error eliminando tenant tras fallo: ' . $ex->getMessage());
                }
            }

        //     CentralErrorLog::create([
        //         'message'   => $e->getMessage(),
        //         'exception' => get_class($e),
        //         'file'      => $e->getFile(),
        //         'line'      => $e->getLine(),
        //     ]);               

      
        // toast()
        //     ->danger('error')
        //     ->pushOnNextPage();

        //     return back();
        }
    }
}
