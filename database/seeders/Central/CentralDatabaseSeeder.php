<?php

namespace Database\Seeders\Central;

use App\Models\Central\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentralDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        User::factory()->create([
            'rut' => '66666666',
            'name' => 'RODRIGO',
            'apellido_paterno' => 'ROJAS',
            'apellido_materno' => 'RUIZ',
            'email' => 'superadmin@aprsoft.cl',
        ])->assignRole('SuperAdmin');

        User::factory()->create([
            'rut' => '11111111',
            'name' => 'admin',
            'apellido_paterno' => 'ROJAS',
            'apellido_materno' => 'RUIZ',
            'email' => 'admin@aprsoft.cl',
        ])->assignRole('Admin');
    }
}