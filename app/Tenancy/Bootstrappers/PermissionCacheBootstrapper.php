<?php

namespace App\Tenancy\Bootstrappers;

use Illuminate\Contracts\Foundation\Application;
use Spatie\Permission\PermissionRegistrar;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class PermissionCacheBootstrapper implements TenancyBootstrapper
{
    public function __construct(protected Application $app) {}

    public function bootstrap(Tenant $tenant): void
    {
        $this->app[PermissionRegistrar::class]->cacheKey = 
            'spatie.permission.cache.tenant.' . $tenant->id;
    }

    public function revert(): void
    {
        $this->app[PermissionRegistrar::class]->cacheKey = 'spatie.permission.cache';
    }
}