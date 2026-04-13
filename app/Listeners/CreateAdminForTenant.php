<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Facades\Tenancy;
use App\Events\NewTenantUserCreated;
use Illuminate\Support\Facades\Log;
use App\Models\User;

/**
 * Listener que se encarga de crear un usuario en la base de datos
 * de un tenant recién creado, con manejo de errores.
 */
class CreateAdminForTenant
{
    /**
     * Maneja el evento TenantCreated.
     *
     * @param TenantCreated $event
     * @return void
     */
    public function handle(NewTenantUserCreated $event)
    {
        $tenant = $event->tenant;        

        try {
            // Inicializamos el contexto del tenant para trabajar en su BD
            Tenancy::initialize($tenant);

            // Definimos los datos del usuario administrador
            $subdomain = $tenant->id; // usar el id del tenant como nombre
            $centralDomain = config('tenancy.central_domains')[0] ?? 'example.com';
            // $email = $subdomain . '@' . $centralDomain;
            $email = $subdomain . '@aprsoft.cl';

            // Insertar el usuario en la tabla 'users' del tenant
            DB::table('users')->insert([
                'rut'=>'111111111',
                'name' => 'administrador',
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Crear usuarios de prueba con factory EN LA BD DEL TENANT
            if (config('app.debug')) {               
                User::factory(10)->create();
            }

        } catch (\Exception $e) {
            // Loguear el error para depuración
            Log::error('Error creando usuario admin para tenant ' . $tenant->id . ': ' . $e->getMessage());
            
        } finally {
            // Finalizamos el contexto del tenant, asegurando que siempre se ejecute
            Tenancy::end();
        }
    }
}
