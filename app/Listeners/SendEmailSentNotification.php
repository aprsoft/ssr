<?php

namespace App\Listeners;

use App\Events\UserEmailSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailSentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEmailSent $event)
    {
        session()->flash(
                'error',
                sprintf(
                    $event->message
                )
            );
       return redirect()->route('central.users.index');
    }
}
