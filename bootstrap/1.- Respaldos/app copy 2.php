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
    // ->withRouting(
    //     web: __DIR__.'/../routes/web.php',
    //     commands: __DIR__.'/../routes/console.php',
      //  channels: __DIR__.'/../routes/channels.php',
    //     health: '/up',
    //     using: function(){ 
            
    //         $centralDomains = config('tenancy.central_domains');
    //         $currentHost = request()->getHost();
            
    //         // 1. Rutas CENTRALES: solo en dominios centrales
    //         foreach ($centralDomains as $domain) {
    //             Route::middleware('web')
    //                 ->domain($domain)
    //                 ->group(base_path('routes/central.php'));
    //         }
            
    //         // 2. Rutas WEB: solo en dominios centrales (landing page)
    //         if (in_array($currentHost, $centralDomains)) {
    //             Route::middleware('web')
    //                 ->domain($currentHost)
    //                 ->group(base_path('routes/web.php'));
    //         }
            
    //         // 3. Rutas TENANT: en cualquier otro dominio
    //         else {
    //             Route::middleware([
    //                 'web',
    //                 InitializeTenancyByDomain::class,
    //                 PreventAccessFromCentralDomains::class,
    //             ])->group(base_path('routes/tenant.php'));
    //         }
    //     }
       
    // )
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        using: function() {
            $centralDomains = config('tenancy.central_domains');

            // 1. Rutas CENTRALES: registradas con constraint de dominio
            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/central.php'));
            }

            // 2. Rutas WEB (landing): registradas con constraint de dominio
            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }

            // 3. Rutas TENANT: siempre registradas, el middleware filtra el acceso
            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
                
               
            ])->group(base_path('routes/tenant.php'));
        }
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('universal', []);

        // $middleware->redirectGuestsTo(function (Request $request) {
        //     if (app(Tenancy::class)->initialized) {
        //         return route('tenant.login');
        //     }
        //     return route('central.login');
        // });

        // $middleware->redirectGuestsTo(function (Request $request) {
        //     $centralDomains = config('tenancy.central_domains');

        //     if (in_array($request->getHost(), $centralDomains)) {
        //         return route('central.login');
        //     }

        //     return route('tenant.login');
        // });
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        
        // $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
        //     return response()->view('errors.tenant-not-found', [], 404);
        // });
        
    })->create();