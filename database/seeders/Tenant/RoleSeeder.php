<?php

namespace Database\Seeders\Tenant;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'SuperAdmin']);
        $role2 = Role::create(['name' => 'Admin']);

        Permission::create(['name' => 'tenant.users.index'])->syncRoles($role1,$role2);
        Permission::create(['name' => 'tenant.users.create'])->syncRoles($role1,$role2);
        Permission::create(['name' => 'tenant.users.edit'])->syncRoles($role1);
        Permission::create(['name' => 'tenant.users.destroy'])->syncRoles($role1);


    }
}
