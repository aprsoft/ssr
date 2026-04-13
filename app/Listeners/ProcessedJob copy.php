<?php

namespace App\Listeners;

use App\Events\UserEmailSent;
use App\Jobs\SendEmailJob;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Log;


class ProcessedJob
{
    public function handle(JobProcessed $event)
    {
        // $payload = $event->job->payload();
        // $instance = null;

    //     if (isset($payload['data']['command'])) {
    //         try {
    //             $instance = unserialize($payload['data']['command']);
    //         } catch (\Throwable $e) {
    //             $instance = null;
    //         }
    //     }

        // $jobName = $instance ? get_class($instance) : $event->job->resolveName();
        $jobName= $event->job->resolveName();

    //    log::info(SendMailJob::class);

        switch ($jobName) {
             case SendEmailJob::class:          
                event(new UserEmailSent('Mensaje enviado'));             
            break;
        }
    }

    private function resolveJobName($event)
    {
        $payload = $event->job->payload();
        $instance = null;

        if (isset($payload['data']['command'])) {
            try {
                $instance = unserialize($payload['data']['command']);
            } catch (\Throwable $e) {
                $instance = null;
            }
        }

        return $instance ? get_class($instance) : $event->job->resolveName();
    }
}
