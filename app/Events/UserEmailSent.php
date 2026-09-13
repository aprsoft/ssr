<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class UserEmailSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(
        public string $scope,
        public int $userId,
        public ?string $tenantId,
        public string $message
    ) {
    }

    public function broadcastOn(): array
    {
        if ($this->scope === 'tenant' && $this->tenantId !== null) {
            return [
                new PrivateChannel(
                    'ssr.tenant.'
                    .$this->tenantId
                    .'.user.'
                    .$this->userId
                ),
            ];
        }

        return [
            new PrivateChannel(
                'ssr.central.user.'.$this->userId
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user-email-sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}