<?php

namespace App\Listeners;

use App\Events\UserEmailSent;
use App\Jobs\SendEmailJob;
use App\Services\Error\ErrorLogger;
use Illuminate\Queue\Events\JobProcessed;
use Throwable;

class ProcessedJob
{
    public function handle(JobProcessed $event): void
    {
        if ($event->job->resolveName() !== SendEmailJob::class) {
            return;
        }

        $payload = $event->job->payload();

        $notification = $payload['ssr_notification'] ?? null;

        if (! is_array($notification)) {
            return;
        }

        $scope = $notification['scope'] ?? null;
        $userId = $notification['user_id'] ?? null;
        $tenantId = $notification['tenant_id'] ?? null;

        if (
            ! in_array($scope, ['central', 'tenant'], true)
            || ! is_numeric($userId)
        ) {
            return;
        }

        if ($scope === 'tenant' && ! is_string($tenantId)) {
            return;
        }

        try {
            event(
                new UserEmailSent(
                    scope: $scope,
                    userId: (int) $userId,
                    tenantId: $tenantId,
                    message: 'Correo enviado correctamente.'
                )
            );
        } catch (Throwable $exception) {
            app(ErrorLogger::class)->report(
                $exception,
                [
                    'operation' => 'queue.job.processed.broadcast',
                    'error_type' => 'broadcast',
                    'job' => SendEmailJob::class,
                    'job_id' => $event->job->getJobId(),
                    'job_uuid' => $event->job->uuid(),
                    'scope' => $scope,
                    'tenant_id' => $tenantId,
                    'user_id' => (int) $userId,
                ]
            );
        }
    }
}