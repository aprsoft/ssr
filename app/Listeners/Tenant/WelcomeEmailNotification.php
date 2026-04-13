<?php

// app/Listeners/SendUserCreatedEmail.php
namespace App\Listeners\Tenant;

use App\Events\Tenant\UserCreated;
use App\Jobs\SendEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WelcomeEmailNotification implements ShouldQueue
{
    public function handle(UserCreated $event)
    {
        Log::info('Listener.WelcomeEmailNotification');

        if ($event->plainPassword) {       

            $payload = [
                'to' => $event->user->email,                
                'subjectLine' => 'Bienvenido !! Eres Nuevo Usuariodel sistema Aprsoft',
                'viewName' => 'tenant.email.user.user-created',
                'data'=>$event->user->setAttribute('plainPassword', $event->plainPassword),                               
            ];

            SendEmailJob::dispatch($payload);
        }
    }
}
