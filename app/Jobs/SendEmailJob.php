<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Services\Error\ErrorLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public array $payload;

    public ?string $tenantId = null;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        $this->tenantId = tenant()?->id;
    }

    public function handle(): void
    {
        Mail::to($this->payload['to'])
            ->send(
                new GenericMail(
                    subjectLine: $this->payload['subjectLine'],
                    viewName: $this->payload['viewName'],
                    data: $this->payload['data'],
                    customAttachments:
                        $this->payload['customAttachments'] ?? []
                )
            );
    }

    public function failed(?Throwable $exception): void
    {
        if (! $exception) {
            return;
        }

        app(ErrorLogger::class)->report(
            $exception,
            [
                'operation' => 'email.send',
                'tenant_id' => $this->tenantId,
                'recipient' => $this->payload['to'] ?? null,
                'error_type' => 'queue',
            ]
        );
    }
}