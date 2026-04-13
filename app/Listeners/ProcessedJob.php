<?php

namespace App\Listeners;

use App\Events\UserEmailSent;
use App\Jobs\SendEmailJob;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Log;



class ProcessedJob
{
   

  public function handle(JobProcessed $event): void
{
    $jobName = $event->job->resolveName();

    Log::info($jobName);

    // ✅ Ignorar jobs internos de broadcast para evitar loop
    if (str_contains($jobName, 'BroadcastEvent')) {
        return;
    }

    switch ($jobName) {
        case SendEmailJob::class:
            event(new UserEmailSent('Correo enviado correctamente'));
            break;
    }
}

}
