<?php

namespace Database\Seeders\Tenant;

// use App\Models\Tenant\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RolePermissionSeeder::class,    
            // ChileAdministrativeDivisionsSeeder::class ,
            // CompanySeeder::class,
            UserSeeder::class
            // TypedocumentSeeder::class,
            // TaxeSeeder::class,
            // StretcheSeeder::class,            
            // // SectorSeeder::class,
            // CustomerSeeder::class,
            // MeterSeeder::class,
            // CustomerMeterSeeder::class,   
            // ServiceSeeder::class,               
            
            // ChileCitiesSeeder::class,      
            
            // DocumentSeeder::class,     
               
        ]);
        // User::factory(5)->create();
        // Document::factory(100)->create();

    

        // User::factory()->create([
        //     'rut' => '66666666', 
        //     'name' => 'RO',  
        //     'apellido_paterno'=>'ROJAS', 
        //     'apellido_materno'=> 'RUIZ',  
        //     'email' => 'desarrollo@aprsoft.cl',
        //     'movil' => '123456789',
        // ]);
    }
}
