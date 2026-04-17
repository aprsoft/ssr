<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Autenticación por Defecto
    |--------------------------------------------------------------------------
    |
    | Esta opción define el "guard" de autenticación y el "broker" de
    | restablecimiento de contraseñas predeterminados para la aplicación.
    | Puedes cambiar estos valores según sea necesario.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de Autenticación
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir cada guard de autenticación de la aplicación.
    | La configuración predeterminada utiliza almacenamiento en sesión
    | junto con el proveedor de usuarios Eloquent.
    |
    | Soportado: "session"
    |
    */

    'guards' => [

        // Guard del dominio central (por defecto)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard para autenticar a los usuarios de los tenants
        'tenant' => [
            'driver' => 'session',
            'provider' => 'tenant_users',
        ],

        'customer' => [
            'driver' => 'session',
            'provider' => 'customers', // ← apunta aquí
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de Usuarios
    |--------------------------------------------------------------------------
    |
    | Cada guard de autenticación tiene un proveedor de usuarios que define
    | cómo se recuperan los usuarios de la base de datos u otro sistema de
    | almacenamiento. Si tienes múltiples tablas o modelos de usuarios,
    | puedes configurar múltiples proveedores y asignarlos a los guards.
    |
    | Soportado: "database", "eloquent"
    |
    */

    'providers' => [

        // Proveedor del dominio central (viene por defecto)
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Proveedor de los usuarios de cada tenant
        'tenant_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tenant\User::class,
        ],

        // Proveedor de los clientes de cada tenant
        'customers' => [  
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restablecimiento de Contraseñas
    |--------------------------------------------------------------------------
    |
    | Estas opciones configuran el comportamiento del restablecimiento de
    | contraseñas de Laravel, incluyendo la tabla para almacenar tokens y
    | el proveedor de usuarios utilizado para recuperarlos.
    |
    | El tiempo de expiración es la cantidad de minutos que cada token será
    | válido. El throttle es la cantidad de segundos que un usuario debe
    | esperar antes de generar nuevos tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'customers' => [           // ← Reset de password para customers
        'provider' => 'customers',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de Confirmación de Contraseña
    |--------------------------------------------------------------------------
    |
    | Define la cantidad de segundos antes de que expire la ventana de
    | confirmación de contraseña y se le pida al usuario que la ingrese
    | nuevamente. Por defecto, el tiempo de espera es de tres horas.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];