<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\DashboardController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'web',
    // InitializeTenancyByDomain::class,
    // PreventAccessFromCentralDomains::class,
])->group(function () {
    


    Route::middleware(['auth:customer'])->name('customer.')->prefix('customer')->group(function () {   

    /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('dashboard', DashboardController::class)->name('dashboard');


    });

    require __DIR__.'/auth/customer/auth.php';

});

