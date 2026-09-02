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
       
        User::factory(5)->create();       

        User::factory()->create([          
            'name' => 'superadmin',              
            'email' => 'admin@aprsoft.cl',
        ]);
        
    }
}
