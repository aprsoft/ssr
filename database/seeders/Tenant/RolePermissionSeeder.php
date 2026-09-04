<?php

namespace Database\Seeders\Tenant;

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
            'guard_name' => 'tenant',
        ]);

        $role2 = Role::create([
            'name' => 'Admin',
            'guard_name' => 'tenant',
        ]);

        Permission::create([
            'name' => 'tenant.users.index',
            'guard_name' => 'tenant',
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'tenant.users.create',
            'guard_name' => 'tenant',
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'tenant.users.edit',
            'guard_name' => 'tenant',
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'tenant.users.destroy',
            'guard_name' => 'tenant',
        ])->syncRoles($role1);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}