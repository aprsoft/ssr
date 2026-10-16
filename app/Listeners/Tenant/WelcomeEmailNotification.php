<?php

namespace App\Listeners\Tenant;

use App\Events\Tenant\UserCreated;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Log;


class WelcomeEmailNotification 
{
    public function handle(UserCreated $event)
    {
        Log::info('Listener.WelcomeEmailNotification');

        if ($event->plainPassword) {       

            $payload = [
                'to' => $event->user->email,                
                'subjectLine' => 'Bienvenido !! Eres Nuevo Colobarador de SSR',
                'viewName' => 'tenant.email.user.user-created',
                'data'=>$event->user->setAttribute('plainPassword', $event->plainPassword),                               
            ];

            SendEmailJob::dispatch($payload);
        }
    }
}
