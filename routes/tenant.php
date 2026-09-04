<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ErrorController;
use App\Http\Controllers\Tenant\PermissionController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\UserController;
use Illuminate\Support\Facades\Route;

        Route::get('/', function () {
            return view('tenant.landingpage.landing');
        })->name('tenant.landing');

        Route::middleware('auth:tenant')
            ->name('tenant.')
            ->prefix('tenant')
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('dashboard', DashboardController::class)
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        Route::get('users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

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

require __DIR__.'/auth/tenant/auth.php';