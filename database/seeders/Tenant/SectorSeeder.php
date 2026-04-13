<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sectors')->insert([
            ['id'=>'1','locality_id' => '1','name' => 'La Hacienda','referencia' => NULL,'state' => 'VIGENTE','created_at' => NULL,'updated_at' => NULL],
            ['id'=>'2','locality_id' => '1','name' => 'Palo Colorado','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'3','locality_id' => '1','name' => 'Los Nogales','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'4','locality_id' => '1','name' => 'La Viña','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'5','locality_id' => '1','name' => 'C. Laguna','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'6','locality_id' => '1','name' => 'Villoreo','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'7','locality_id' => '1','name' => 'El Uno','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'8','locality_id' => '1','name' => 'El Huerto','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'9','locality_id' => '1','name' => 'Camino Interior','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'10','locality_id' => '1','name' => 'Los Corrales','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'11','locality_id' => '1','name' => 'La Mancera','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'12','locality_id' => '1','name' => 'Boldad','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'13','locality_id' => '1','name' => 'Refugio','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'14','locality_id' => '1','name' => 'V. Mar','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'15','locality_id' => '1','name' => 'Encierra','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'16','locality_id' => '1','name' => 'Villa Esperanza','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'17','locality_id' => '1','name' => 'La Mestiza','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'18','locality_id' => '1','name' => 'Los Mayos','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'19','locality_id' => '1','name' => 'Lomas de Catapilco','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
            ['id'=>'20','locality_id' => '1','name' => 'Por Definir','referencia' => NULL,'state' => 'VIGENTE','created_at' => '2026-02-02 23:22:08','updated_at' => '2026-02-02 23:22:08'],
        ]);
    }
}
