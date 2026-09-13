<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class JobNotification implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(
        public string $scope,
        public int $userId,
        public ?string $tenantId,
        public string $type,
        public string $message
    ) {
    }

    public function broadcastOn(): PrivateChannel
    {
        if ($this->scope === 'tenant') {
            return new PrivateChannel(
                sprintf(
                    'ssr.tenant.%s.user.%d',
                    $this->tenantId,
                    $this->userId
                )
            );
        }

        return new PrivateChannel(
            'ssr.central.user.'.$this->userId
        );
    }

    public function broadcastAs(): string
    {
        return 'job-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
        ];
    }
}