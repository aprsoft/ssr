<?php

use App\Http\Controllers\Central\UserController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\ErrorController;
use App\Http\Controllers\Central\PermissionController;
use App\Http\Controllers\Central\RoleController;
use App\Http\Controllers\Central\TenantController;
use Illuminate\Support\Facades\Route;

    Route::get('/central', function () {
        return redirect()->route('central.dashboard');
    })->name('central.home'); 


    Route::middleware(['auth'])->name('central.')->prefix('central')->group(function () {
        
        /*  Route::get('dashboard', function () {
            
                
                return view('central.dashboard', ['title' => 'Dashboard']);
            })->name('dashboard'); */

            Route::get('dashboard',DashboardController::class)->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            /*
            |--------------------------------------------------------------------------
            | Tenants
            |--------------------------------------------------------------------------
            */
             // 1. RUTAS ESTÁTICAS PRIMERO (sin parámetros dinámicos)
            Route::get('tenants/listado/{status?}', [TenantController::class, 'index'])
                ->name('tenants.index');

            Route::get('tenants/create', [TenantController::class, 'create'])
                ->name('tenants.create');

            // 2. POST para crear (sin parámetros en URL)
            Route::post('tenants', [TenantController::class, 'store'])
                ->name('tenants.store');

            // 3. RUTAS CON {tenant} - ESPECÍFICAS ANTES QUE GENERALES
            Route::get('tenants/{tenant}/edit', [TenantController::class, 'edit'])
                ->name('tenants.edit');  
            Route::put('tenants/{tenant}', [TenantController::class, 'update'])
                ->name('tenants.update');          

            // 4. RUTAS GENERALES CON {tenant} (al final)
            Route::get('tenants/{tenant}', [TenantController::class, 'show'])
                ->name('tenants.show');

            Route::patch(
                'tenants/{tenant}/suspend',
                [TenantController::class, 'suspend']
            )->name('tenants.suspend');

            Route::patch(
                'tenants/{tenant}/restore',
                [TenantController::class, 'restore']
            )->name('tenants.restore');

            // Route::delete('tenants/{tenant}/delete', [TenantController::class, 'destroy'])
            //     ->name('tenants.destroy');

            /*
            |--------------------------------------------------------------------------
            | Error_log
            |--------------------------------------------------------------------------
            */

             Route::get('errors', [ErrorController::class, 'index'])->name('errors.index');
             Route::get('errors/{errorLog}', [ErrorController::class, 'show'])->name('errors.show');

            /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        Route::get('roles', [RoleController::class, 'index'])
            ->name('roles.index');

        Route::get('roles/create', [RoleController::class, 'create'])
            ->name('roles.create');

        Route::get('roles/{role}', [RoleController::class, 'show'])
            ->name('roles.show');
        
        Route::get('roles/{role}', [RoleController::class, 'edit'])
            ->name('roles.edit');
        
        Route::get('roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update');

        

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        Route::get('permissions', [PermissionController::class, 'index'])
            ->name('permissions.index');

        Route::get('permissions/create', [PermissionController::class, 'create'])
            ->name('permissions.create');

             

            });

    require __DIR__.'/auth/central/auth.php';  

