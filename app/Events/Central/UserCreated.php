<?php

namespace App\Events\Central;

use App\Models\Central\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public ?string $plainPassword = null
    ) {
         Log::info('Event.UserCreated', [
            'tenant_id' => tenant()?->getTenantKey(),
            'user_id' => $user->id,
        ]);
    }
}