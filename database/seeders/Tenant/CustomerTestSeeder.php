<?php

namespace Database\Seeders\Tenant;

use App\Models\Customer;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class CustomerTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(5)->create();

        Customer::factory()->create([
            'rut' => '66666666-6', 
            'name' => 'RODRIGO',  
            'apellido_paterno'=>'ROJAS', 
            'apellido_materno'=> 'RUIZ',  
            'email' => 'admin@aprsoft.cl',
        ]);
        
    }
}

