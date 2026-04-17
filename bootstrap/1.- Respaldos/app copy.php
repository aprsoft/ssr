<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;

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
            Route::middleware('web')->group(base_path('routes/tenant.php'));   
            Route::middleware('web')->group(base_path('routes/web.php'));       
                
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('universal', []);

        $middleware->redirectGuestsTo(function (Request $request) {
            if (app(Tenancy::class)->initialized) {
                return route('tenant.login');
            }
            return route('central.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
