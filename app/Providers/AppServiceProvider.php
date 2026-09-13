<?php

namespace App\Providers;

use App\Services\Queue\JobFailureHandler;
use App\Services\Queue\JobSuccessHandler;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SuperAdmin')
                ? true
                : null;
        });

        /*
        |--------------------------------------------------------------------------
        | Contexto del usuario que originó el Job
        |--------------------------------------------------------------------------
        |
        | Se agrega a cualquier Job despachado desde una petición autenticada.
        | Si el Job no necesita notificar al usuario, simplemente será ignorado
        | posteriormente por JobNotificationService.
        |
        */
        Queue::createPayloadUsing(
            function (
                string $connection,
                ?string $queue,
                array $payload
            ): array {
                /*
                 * Contexto Tenant.
                 *
                 * Si tenancy está inicializado, nunca debemos caer al guard web.
                 */
                if (tenant() !== null) {
                    $userId = auth('tenant')->id();

                    if (! $userId) {
                        return [];
                    }

                    return [
                        'ssr_notification' => [
                            'scope' => 'tenant',
                            'tenant_id' => (string) tenant()->getTenantKey(),
                            'user_id' => (int) $userId,
                        ],
                    ];
                }

                /*
                 * Contexto Central.
                 */
                $userId = auth('web')->id();

                if (! $userId) {
                    return [];
                }

                return [
                    'ssr_notification' => [
                        'scope' => 'central',
                        'tenant_id' => null,
                        'user_id' => (int) $userId,
                    ],
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Job procesado correctamente
        |--------------------------------------------------------------------------
        */
        Queue::after(function (JobProcessed $event): void {
            app(JobSuccessHandler::class)->handle($event);
        });

        /*
        |--------------------------------------------------------------------------
        | Job fallido
        |--------------------------------------------------------------------------
        */
        Queue::failing(function (JobFailed $event): void {
            app(JobFailureHandler::class)->handle($event);
        });
    }
}