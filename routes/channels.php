<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'ssr.central.user.{userId}',
    function ($user, int $userId): bool {
        return tenant() === null
            && (int) $user->getAuthIdentifier() === $userId;
    },
    [
        'guards' => ['web'],
    ]
);

Broadcast::channel(
    'ssr.tenant.{tenantId}.user.{userId}',
    function (
        $user,
        string $tenantId,
        int $userId
    ): bool {
        if (tenant() === null) {
            return false;
        }

        return (string) tenant()->getTenantKey() === $tenantId
            && (int) $user->getAuthIdentifier() === $userId;
    },
    [
        'guards' => ['tenant'],
    ]
);