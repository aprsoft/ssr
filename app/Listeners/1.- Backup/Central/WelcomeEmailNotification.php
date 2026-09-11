<?php

namespace App\Listeners\Central;

use App\Events\Central\UserCreated;
use App\Jobs\SendEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmailNotification implements ShouldQueue
{
    public function handle(UserCreated $event): void
    {
        if (! $event->plainPassword) {
            return;
        }

        $payload = [
            'to' => $event->user->email,

            'subjectLine' => 'Bienvenido al sistema Aprsoft',

            'viewName' => 'central.email.user.user-created',

            'data' => $event->user->setAttribute(
                'plainPassword',
                $event->plainPassword
            ),
        ];

        SendEmailJob::dispatch($payload);
    }
}