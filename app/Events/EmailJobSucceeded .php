<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class UserEmailSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $message;

    public function __construct($message)
    {   
       
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['user-channel'];
    }

    public function broadcastAs()
    {
        return 'user-email-sent';
    }
}
