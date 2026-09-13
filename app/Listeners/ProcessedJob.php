<?php

namespace App\Listeners;

use App\Events\UserEmailSent;
use App\Jobs\SendEmailJob;
use Illuminate\Queue\Events\JobProcessed;
class ProcessedJob
{
   

    public function handle(JobProcessed $event)
    {
        $jobName = $event->job->resolveName();        
      
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
