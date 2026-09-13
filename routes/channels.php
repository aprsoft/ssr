<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'App.Models.User.{id}',
    function ($user, $id) {
        return (int) $user->id === (int) $id;
    }
);

/*
|--------------------------------------------------------------------------
| Usuario Central
|--------------------------------------------------------------------------
*/
Broadcast::channel(
    'ssr.central.user.{userId}',
    function ($user, $userId): bool {
        if (tenant() !== null) {
            return false;
        }

        return (int) $user->getAuthIdentifier()
            === (int) $userId;
    },
    [
        'guards' => ['web'],
    ]
);

/*
|--------------------------------------------------------------------------
| Usuario Tenant
|--------------------------------------------------------------------------
*/
Broadcast::channel(
    'ssr.tenant.{tenantId}.user.{userId}',
    function (
        $user,
        $tenantId,
        $userId
    ): bool {
        if (tenant() === null) {
            return false;
        }

        return (string) tenant()->getTenantKey()
                === (string) $tenantId
            && (int) $user->getAuthIdentifier()
                === (int) $userId;
    },
    [
        'guards' => ['tenant'],
    ]
);