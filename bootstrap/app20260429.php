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
        health: '/up',
        using: function(){ 
            
            $domains=config('tenancy.central_domains');

            foreach ($domains as $domain) {
                Route::middleware('web')
                ->domain($domain)
                ->group(base_path('routes/central.php'));
            }           <?php

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
        health: '/up',
        using: function(){ 
            
            $domains=config('tenancy.central_domains');

            foreach ($domains as $domain) {
                Route::middleware('web')
                ->domain($domain)
                ->group(base_path('routes/central.php'));
            }
            // Route::middleware('web')->group(base_path('routes/central.php'));    
            // Route::middleware('web')->group(base_path('routes/tenant.php'));  

            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ])->group(base_path('routes/tenant.php')); 


            // Route::middleware('web')->group(base_path('routes/web.php')); 

            // Solo dominio central para el sitio web
                Route::middleware('web')
                    ->domain(config('tenancy.central_domains')[0])
                    ->group(base_path('routes/web.php'));

                    
                Route::middleware('web')->group(base_path('routes/customer.php'));        
                
        }
    )

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
            return route('customer.login');
        });

        // REDIRECCIÓN PARA USUARIOS AUTENTICADOS (evita que vean login)
        $middleware->redirectUsersTo(function (Request $request) {
            $centralDomains = config('tenancy.central_domains');
            $host = $request->getHost();

            if (in_array($host, $centralDomains)) {
                return route('central.dashboard');
            }

            return route('tenant.dashboard');
            return route('customer.dashboard');
        });
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            return response()->view('errors.tenant-not-found', [], 404);
        });
        
    })->create();
            
            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ])->group(base_path('routes/tenant.php')); 


            // Solo dominio central para el sitio web
                Route::middleware('web')
                    ->domain(config('tenancy.central_domains')[0])
                    ->group(base_path('routes/web.php'));


                    
                Route::middleware('web')->group(base_path('routes/customer.php'));        
                
        }
    )

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
            return route('customer.login');
        });

        // REDIRECCIÓN PARA USUARIOS AUTENTICADOS (evita que vean login)
        $middleware->redirectUsersTo(function (Request $request) {
            $centralDomains = config('tenancy.central_domains');
            $host = $request->getHost();

            if (in_array($host, $centralDomains)) {
                return route('central.dashboard');
            }

            return route('tenant.dashboard');
            return route('customer.dashboard');
        });
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
            return response()->view('errors.tenant-not-found', [], 404);
        });
        
    })->create();