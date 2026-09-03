<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Central\Controller;
use App\Models\ErrorLog;
use App\Models\Central\Tenant;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
 use Illuminate\Http\Request;
use Throwable;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Route;

// use Illuminate\Validation\ValidationException;
// use App\Events\NewTenantUserCreated;
// use App\Models\CentralErrorLog;
// use Stancl\Tenancy\Database\Models\Domain;
// use Illuminate\Http\Request;

class TenantController extends Controller
{
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
        return view('central.tenants.show');
    }

    public function edit(Tenant $tenant)
    {
      
        return view('central.tenants.edit');
    }

    public function update(Tenant $tenant)
    {
        return view('central.tenants.show');
    }

    public function store()
    {
        $tenant = null;

        try {
            $data = request()->all();

            // Solo en desarrollo
            // logger()->info('Datos recibidos en store(): ', $data);

            $validator = Validator::make($data, [
                'id' => 'required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->with('error', 'Error de validación. Revisa los datos.');
            }

            if (Tenant::where('id', $data['id'])->exists()) {
                return back()->with('error', "El Tenant con ID '{$data['id']}' ya existe.");
            }

            $centralDomains = config('tenancy.central_domains');
            if (empty($centralDomains) || !is_array($centralDomains)) {
                throw new \Exception('No hay dominios centrales configurados.');
            }

            // Ambas operaciones dentro de la misma transacción
            // DB::transaction(function () use ($data, $centralDomains, &$tenant) {
            //     $tenant = Tenant::create(['id' => $data['id']]);
            //     $tenant->domains()->create([
            //         'domain' => $data['id'] . '.' . $centralDomains[0],
            //     ]);
            // });
   
                $tenant = Tenant::create(['id' => $data['id']]);
              
                $tenant->domains()->create([
                    'domain' => $data['id'] . '.' . $centralDomains[0],
                ]);

            return redirect()
                ->route('central.dashboard')
                ->with('success', "Tenant '{$data['id']}' creado correctamente.");

        } catch (QueryException $e) {
            // Antes que Throwable para que pueda alcanzarse
            
            Log::error('Error SQL al crear tenant', [
                'sql'      => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            ErrorLog::create([
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);

            return back()->with('error', 'Error de base de datos al crear la BD del tenant. Crear la BD de formal manual (verificar los prefijos por defecto de las nuevas BD,tenant_id y dominio), Tenancy montado en un servidor COMPARTIDO');

        } catch (Throwable $e) {
                 

            Log::error('Error inesperado al crear tenant: ' . $e->getMessage());

            ErrorLog::create([
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);

            return back()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function suspend(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()
            ->route('central.tenants.index', ['status' => 'active'])
            ->with('success', 'Tenant suspendido correctamente.');
    }

    public function restore(string $id)
    {
        $tenant = Tenant::onlyTrashed()->findOrFail($id);

        $tenant->restore();

        return redirect()
            ->route('central.tenants.index', ['status' => 'suspended'])
            ->with('success', 'Tenant restaurado correctamente.');
    }

    public function destroy(Tenant $tenant)
    {
        // Buscar tenant por ID
        // $tenant = Tenant::find($id);

         dd($tenant);

        if (! $tenant) {
            return redirect()->back()->with('error', 'El tenant no existe.');
        }

        // Eliminar tenant, dominios y recursos asociados
        $tenant->delete();

        return redirect()->route('central.dashboard')->with('success', 'Tenant eliminado');
    }

    
}
