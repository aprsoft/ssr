<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        using: function() {
            $centralDomains = config('tenancy.central_domains', []);
            $currentHost = request()->getHost();

            // 1. Rutas CENTRALES: solo en dominios centrales
            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/central.php'));
            }

            // 2. Verificar si es dominio central o subdominio (tenant)
            if (in_array($currentHost, $centralDomains)) {
                Route::middleware('web')
                    ->domain($currentHost)
                    ->group(base_path('routes/web.php'));
            } else {
                // Subdominio de tenant: cargar rutas de tenant + customer
                Route::middleware([
                    'web',
                    InitializeTenancyByDomain::class,
                    PreventAccessFromCentralDomains::class,
                ])->group(function () {
                    // Rutas de administración del tenant (empleados)
                    require base_path('routes/tenant.php');
                    
                    // Rutas del portal del cliente (moradores)
                    require base_path('routes/customer.php');
                });
            }
        }
    )

    // ->withRouting(
    //     web: __DIR__.'/../routes/web.php',
    //     commands: __DIR__.'/../routes/console.php',
    //     channels: __DIR__.'/../routes/channels.php',
    //     health: '/up',
    //     using: function() {
    //         $centralDomains = config('tenancy.central_domains');

    //         // 1. Rutas CENTRALES: registradas con constraint de dominio
    //         foreach ($centralDomains as $domain) {
    //             Route::middleware('web')
    //                 ->domain($domain)
    //                 ->group(base_path('routes/central.php'));
    //         }

    //         Route::middleware('web')->group(base_path('routes/tenant.php'));   
    //         Route::middleware('web')->group(base_path('routes/web.php')); 
    //         Route::middleware('web')->group(base_path('routes/customer.php'));   
    //     }
    // )


//     ->withRouting(
//     web: __DIR__.'/../routes/web.php',
//     commands: __DIR__.'/../routes/console.php',
//     channels: __DIR__.'/../routes/channels.php',
//     health: '/up',
//     using: function() {
//         $centralDomains = config('tenancy.central_domains', []);
//         $currentHost = request()->getHost();

//         // 1. Rutas CENTRALES: solo en dominios centrales
//         foreach ($centralDomains as $domain) {
//             Route::middleware('web')
//                 ->domain($domain)
//                 ->group(base_path('routes/central.php'));
//         }

//         // 2. Verificar si estamos en dominio central o subdominio (tenant)
//         if (in_array($currentHost, $centralDomains)) {
//             // Dominio central: cargar rutas web generales
//             Route::middleware('web')
//                 ->domain($currentHost)
//                 ->group(base_path('routes/web.php'));
//         } else {
//             // Subdominio (tenant): cargar rutas de tenant con middleware de tenancy
//             Route::middleware([
//                 'web',
//                 InitializeTenancyByDomain::class,
//                 PreventAccessFromCentralDomains::class,
//             ])->group(base_path('routes/tenant.php'));
//         }
//     }
// )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('universal', []);

        // REDIRECCIÓN PARA USUARIOS NO AUTENTICADOS (guests)
        $middleware->redirectGuestsTo(function (Request $request) {
            $centralDomains = config('tenancy.central_domains');
            $host = $request->getHost();

            if (in_array($host, $centralDomains)) {
                return route('central.login');
            }

            return route('tenant.login');
            // return route('customer.login');
        });

        // REDIRECCIÓN PARA USUARIOS AUTENTICADOS (evita que vean login)
        $middleware->redirectUsersTo(function (Request $request) {
            $centralDomains = config('tenancy.central_domains');
            $host = $request->getHost();

            if (in_array($host, $centralDomains)) {
                return route('central.dashboard');
            }

            return route('tenant.dashboard');
            //  return route('customer.dashboard');
        });
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            return response()->view('errors.tenant-not-found', [], 404);
        });
        
    })->create();