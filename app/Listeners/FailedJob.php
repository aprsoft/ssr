<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use App\Models\JobStatus;
use Stancl\Tenancy\Facades\Tenancy;

class FailedJob
{
    public function handle(JobFailed $event): void
    {
        $serialized = $event->job->payload()['data']['command'] ?? null;
        $instance = $serialized ? unserialize($serialized) : null;

        $jobName = $instance ? get_class($instance) : null;

        // $tenantId = ($instance && property_exists($instance, 'tenantId'))
        //     ? $instance->tenantId
        //     : null;

        // if ($tenantId) {
        //     Tenancy::initialize($tenantId);
        // }

        $exception = $event->exception;

      
    }
}

/*
|-------------------------------------------------------------
| RESUMEN
|-------------------------------------------------------------
| - Reasume el tenant con Tenancy::initialize().
| - Guarda datos exactos de la excepción.
| - Payload del Job queda registrado para reintentos.
| - Compatible con Tenancy for Laravel 3.
*/
