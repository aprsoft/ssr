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
        | Queue::createPayloadUsing() permite agregar información adicional
        | al payload que Laravel genera al despachar un Job.
        |
        | ¿Qué es el payload?
        |
        | Es la estructura que Laravel guarda en la cola para posteriormente
        | reconstruir y ejecutar el Job.
        |
        | Laravel ya guarda información como:
        |
        |   - UUID
        |   - nombre del Job
        |   - Job serializado
        |   - intentos
        |   - timeout
        |   - fecha de creación
        |
        | Nosotros agregamos:
        |
        |   ssr_notification
        |
        | con información sobre quién originó el Job.
        |
        | Esto es necesario porque cuando el worker procese el Job,
        | la petición HTTP original probablemente ya habrá terminado.
        |
        | En ese momento ya no podemos confiar en:
        |
        |   request()
        |   session()
        |   auth()
        |
        | para saber quién inició originalmente el proceso.
        |
        | Por eso guardamos esa información dentro del propio payload.
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
            /*
             * app() es un helper de Laravel que solicita una instancia
             * al Service Container.
             *
             * JobSuccessHandler::class equivale al nombre completo:
             *
             * App\Services\Queue\JobSuccessHandler
             *
             * Laravel crea automáticamente la instancia y resuelve
             * también las dependencias declaradas en su constructor.
             *
             * Finalmente llamamos:
             *
             *   handle($event)
             *
             * entregándole el JobProcessed recibido desde Laravel.
             */
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


