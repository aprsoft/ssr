<?php

// app/Events/UserCreated.php
namespace App\Events\Tenant;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct(User $user, $plainPassword = null)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    // Canal para todos los usuarios conectados
    public function broadcastOn()
    {
        return ['users']; // canal público
    }

    public function broadcastAs()
    {
        return 'user.created';
    }

    public function broadcastWith()
    {
        return [
            'name'  => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}

