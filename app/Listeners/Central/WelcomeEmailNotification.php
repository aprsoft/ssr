<?php

// app/Listeners/SendUserCreatedEmail.php
namespace App\Listeners\Central;

use App\Events\UserCreated;
use App\Jobs\SendEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmailNotification implements ShouldQueue
{
     public function handle(UserCreated $event)
    {
        if ($event->plainPassword) {       

            $payload = [
                'to' => $event->user->email,                
                'subjectLine' => 'Bienvenido !! Eres Nuevo Usuariodel sistema Aprsoft',
                'viewName' => 'central.email.user.user-created',
                'data'=>$event->user->setAttribute('plainPassword', $event->plainPassword),                               
            ];

            SendEmailJob::dispatch($payload);
        }
    }
}
