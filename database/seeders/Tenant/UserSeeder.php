<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create();

        User::factory()->create([
            'rut' => '66666666', 
            'name' => 'RODRIGO',  
            'apellido_paterno'=>'ROJAS', 
            'apellido_materno'=> 'RUIZ',  
            'email' => 'admin@aprsoft.cl',
        ])->assignRole('SuperAdmin');
        
    }
}

