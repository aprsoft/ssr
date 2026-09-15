<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\User;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantName = tenant('id');       

        User::factory()->create([
            // 'name' => 'SuperAdmin',            
            'email' => 'admin@aprsoft.cl',
        ])->assignRole('SuperAdmin');

        User::factory()->create([        
            // 'name' => 'Admin',              
            'email' => $tenantName.'@aprsoft.cl',
        ])->assignRole('Admin');
        
    }
}

