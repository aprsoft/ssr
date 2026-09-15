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
            Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
         
            /*
            |--------------------------------------------------------------------------
            | Tenants
            |--------------------------------------------------------------------------
            */
          
            Route::get('tenants/listado/{status?}', [TenantController::class, 'index'])
                ->name('tenants.index');

            Route::get('tenants/create', [TenantController::class, 'create'])
                ->name('tenants.create');         
        
            Route::get('tenants/{tenant}/edit', [TenantController::class, 'edit'])
                ->name('tenants.edit');  
          
            Route::get('tenants/{tenant}', [TenantController::class, 'show'])
                ->name('tenants.show');            

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

            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
                ->name('roles.edit');
            
            Route::put('roles/{role}', [RoleController::class, 'update'])
                ->name('roles.update');
            
            Route::get('roles/{role}', [RoleController::class, 'show'])
                ->name('roles.show');        

        

            /*
            |--------------------------------------------------------------------------
            | Permissions
            |--------------------------------------------------------------------------
            */

            Route::get('permissions', [PermissionController::class, 'index'])
                ->name('permissions.index');

            Route::get('permissions/create', [PermissionController::class, 'create'])
                ->name('permissions.create');

            Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])
                ->name('permissions.edit');

            Route::get('permissions/{permission}', [PermissionController::class, 'show'])
                ->name('permissions.show');

             

            });

    require __DIR__.'/auth/central/auth.php';  

