<?php

namespace App\Listeners\Central;

use App\Events\Central\UserCreated;
use App\Jobs\SendEmailJob;

class WelcomeEmailNotification
{
    public function handle(UserCreated $event): void
    {
        if (! $event->plainPassword) {
            return;
        }

        $payload = [
            'to' => $event->user->email,

            'subjectLine' => 'Bienvenido !! Eres Nuevo Usuario del sistema Aprsoft',

            'viewName' => 'central.email.user.user-created',

            'data' => $event->user->setAttribute(
                'plainPassword',
                $event->plainPassword
            ),
        ];

        SendEmailJob::dispatch($payload);
    }
}