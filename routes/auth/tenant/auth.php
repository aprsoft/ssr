<?php

use App\Http\Controllers\Tenant\Auth\ConfirmationController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\Auth\NewPasswordController;
use App\Http\Controllers\Tenant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Tenant\Auth\RegistrationController;
use App\Http\Controllers\Tenant\Auth\VerificationController;
use App\Http\Controllers\Tenant\Settings\PasswordController;
use App\Http\Controllers\Tenant\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Login tenant
|--------------------------------------------------------------------------
*/

Route::middleware('guest:tenant')
    ->name('tenant.')
    ->prefix('tenant')
    ->group(function () {
        Route::get('login', [LoginController::class, 'create'])
            ->name('login');

        Route::post('login', [LoginController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Registro y recuperación
|--------------------------------------------------------------------------
|
| Estos controladores todavía deben corregirse para utilizar explícitamente
| el modelo, guard y password broker del tenant.
|
*/

Route::middleware('guest')
    ->name('tenant.')
    ->prefix('tenant')
    ->group(function () {

        Route::get('register', [RegistrationController::class, 'create'])
            ->name('register');

        Route::post('register', [RegistrationController::class, 'store']);

        Route::get(
            'forgot-password',
            [PasswordResetLinkController::class, 'create']
        )->name('password.request');

        Route::post(
            'forgot-password',
            [PasswordResetLinkController::class, 'store']
        )->name('password.email');

        Route::get(
            'reset-password/{token}',
            [NewPasswordController::class, 'create']
        )->name('password.reset');

        Route::post(
            'reset-password',
            [NewPasswordController::class, 'store']
        )->name('password.store');
    });

/*
|--------------------------------------------------------------------------
| Usuario tenant autenticado
|--------------------------------------------------------------------------
*/

Route::middleware('auth:tenant')
    ->name('tenant.')
    ->prefix('tenant')
    ->group(function () {

        Route::get(
            'verify-email',
            [VerificationController::class, 'notice']
        )->name('verification.notice');

        Route::post(
            'verify-email',
            [VerificationController::class, 'store']
        )
            ->middleware('throttle:6,1')
            ->name('verification.store');

        Route::get(
            'verify-email/{id}/{hash}',
            [VerificationController::class, 'verify']
        )
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::get(
            'confirm-password',
            [ConfirmationController::class, 'create']
        )->name('password.confirm');

        Route::post(
            'confirm-password',
            [ConfirmationController::class, 'store']
        )->name('confirmation.store');

        Route::get(
            'settings/profile',
            [ProfileController::class, 'edit']
        )->name('settings.profile.edit');

        Route::put(
            'settings/profile',
            [ProfileController::class, 'update']
        )->name('settings.profile.update');

        Route::delete(
            'settings/profile',
            [ProfileController::class, 'destroy']
        )->name('settings.profile.destroy');

        Route::get(
            'settings/password',
            [PasswordController::class, 'edit']
        )->name('settings.password.edit');

        Route::put(
            'settings/password',
            [PasswordController::class, 'update']
        )->name('settings.password.update');

        Route::post(
            'logout',
            [LoginController::class, 'destroy']
        )->name('logout');
    });