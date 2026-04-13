<?php

namespace App\Events;

use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento que se dispara después de crear un tenant y sus dominios,
 * para permitir crear un usuario administrador en la base de datos del tenant.
 */
class NewTenantUserCreated
{
    use Dispatchable, SerializesModels;

    public Tenant $tenant;

    /**
     * Crear instancia del evento
     *
     * @param Tenant $tenant
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
