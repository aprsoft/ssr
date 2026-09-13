<?php

namespace App\Providers;

use App\Jobs\SendEmailJob;
use App\Services\Error\ErrorLogger;
use Illuminate\Queue\Events\JobFailed;
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
        | Contexto de notificación de jobs
        |--------------------------------------------------------------------------
        |
        | Permite saber qué usuario y contexto originaron un SendEmailJob.
        | Este dato posteriormente estará disponible en JobProcessed.
        |
        */
        Queue::createPayloadUsing(
            function (
                string $connection,
                ?string $queue,
                array $payload
            ): array {
                if (($payload['displayName'] ?? null) !== SendEmailJob::class) {
                    return [];
                }

                /*
                 * Contexto Tenant.
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

        Queue::failing(function (JobFailed $event): void {
            $payload = $event->job->payload();

            app(ErrorLogger::class)->report(
                $event->exception,
                [
                    'operation' => 'queue.job.failed',
                    'error_type' => 'queue',
                    'connection' => $event->connectionName,
                    'job_id' => $event->job->getJobId(),
                    'job_uuid' => $event->job->uuid(),
                    'job' => $payload['displayName'] ?? null,
                ]
            );
        });
    }
}