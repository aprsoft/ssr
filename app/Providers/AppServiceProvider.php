<?php

namespace App\Providers;

use App\Services\Error\ErrorLogger;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SuperAdmin')
                ? true
                : null;
        });

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