<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Central\Controller;
use App\Models\Central\Tenant;
use App\Services\Error\ErrorLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TenantController extends Controller
{
    public function __construct(
        protected ErrorLogger $errorLogger
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->status;

        return view('central.tenants.index', compact('status'));
    }

    public function create()
    {
        return view('central.tenants.create');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('domains');

        return view('central.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('domains');

        return view('central.tenants.edit', compact('tenant'));
    }

    public function update(Tenant $tenant)
    {
        return view('central.tenants.show');
    }

    public function store()
    {
        try {
            $data = request()->all();

            $validator = Validator::make($data, [
                'id' => 'required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('error', 'Error de validación. Revisa los datos.');
            }

            if (Tenant::where('id', $data['id'])->exists()) {
                return back()
                    ->with('error', "El Tenant con ID '{$data['id']}' ya existe.");
            }

            $centralDomains = config('tenancy.central_domains');

            if (empty($centralDomains) || ! is_array($centralDomains)) {
                throw new \Exception('No hay dominios centrales configurados.');
            }

            $tenant = Tenant::create([
                'id' => $data['id'],
            ]);

            $tenant->domains()->create([
                'domain' => $data['id'] . '.' . $centralDomains[0],
            ]);

            return redirect()
                ->route('central.tenants.index')
                ->with(
                    'success',
                    "Tenant '{$data['id']}' creado correctamente."
                );
        } catch (QueryException $e) {
            $this->errorLogger->report($e, [
                'operation' => 'tenant.store',
                'tenant_id' => $data['id'] ?? null,
                'error_type' => 'database',
            ]);

            return back()->with(
                'error',
                'Error de base de datos al crear la BD del tenant. Crear la BD de forma manual (verificar los prefijos por defecto de las nuevas BD, tenant_id y dominio), Tenancy montado en un servidor COMPARTIDO.'
            );
        } catch (Throwable $e) {
            $this->errorLogger->report($e, [
                'operation' => 'tenant.store',
                'tenant_id' => $data['id'] ?? null,
                'error_type' => 'unexpected',
            ]);

            return back()->with(
                'error',
                'Ocurrió un error inesperado.'
            );
        }
    }
}