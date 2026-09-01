<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
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
