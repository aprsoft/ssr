<?php

namespace Database\Seeders\Central;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role1 = Role::create([
            'name' => 'SuperAdmin',
            'guard_name' => 'central_users',
        ]);

        $role2 = Role::create([
            'name' => 'Admin',
            'guard_name' => 'central_users',
        ]);

        Permission::create([
            'name' => 'central.users.index',
            'guard_name' => 'central_users',
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'central.users.create',
            'guard_name' => 'central_users',
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'central.users.edit',
            'guard_name' => 'central_users',
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'central.users.destroy',
            'guard_name' => 'central_users',
        ])->syncRoles($role1);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}