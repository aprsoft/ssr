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
            'name' => 'SuperAdmin',            
            'email' => 'superadmin@aprsoft.cl',
        ])->assignRole('SuperAdmin');

        User::factory()->create([            
            'name' => 'Admin',            
            'email' => 'admin@aprsoft.cl',
        ])->assignRole('Admin');
    }
}