<?php

namespace App\Events\Central;

use App\Models\Central\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public ?string $plainPassword = null
    ) {
    }
}