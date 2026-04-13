<?php

namespace App\Jobs;

use App\Events\UserEmailSent;
use App\Mail\GenericMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array  $payload;
    public string $tenantId;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        // Tenancy for Laravel 3: obtener tenant actual
        $this->tenantId = tenant()->id;
    }

    public function handle(): void
    {
        // Eliminar esta excepción cuando terminen las pruebas
         //throw new \Exception("Error forzado en handle()");

        Mail::to($this->payload['to'])
            ->send(new GenericMail(
                subjectLine: $this->payload['subjectLine'],
                viewName: $this->payload['viewName'],
                data: $this->payload['data'],
                customAttachments: $this->payload['customAttachments'] ?? []
            ));

    }

    // ✅ Se ejecuta si el job falló definitivamente
    // public function failed(\Throwable $e): void
    // {
    //     tenancy()->initialize($this->tenantId);
    //     event(new UserEmailFailed('Error al enviar correo: ' . $e->getMessage()));
    // }
}

/*
|-------------------------------------------------------------
| RESUMEN
|-------------------------------------------------------------
| - Guarda tenantId para reactivar el contexto en fallos.
| - Serializa el payload correctamente.
| - Compatible con Tenancy 3 + Laravel 12 + Queue workers.
*/
