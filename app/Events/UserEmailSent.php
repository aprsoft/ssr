<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;

class UserEmailSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $message;

    public function __construct($message)
    {   
       
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [new Channel('user-channel')];
    }

    public function broadcastAs()
    {
        return 'user-email-sent';
    }
}
