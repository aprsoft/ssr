<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StretcheSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('stretches')->truncate();

        DB::table('Stretches')->insert([
            
            [
                'id' => 1,
                'name' => 'TRAMO 1 (1 a 15 METROS CUBICOS)',
                'inicio' => 0,
                'termino' => 15,
                'neto' => 430,
                'iva'=>0,
                'total'=>430,
                'taxe_id' => 2,
                'estado_tramo' => 'VIGENTE',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'TRAMO 2 (16 a 25 METROS CUBICOS)',
                'inicio' => 16,
                'termino' => 25,
                'neto' => 500,
                'iva'=>0,
                'total'=>500,
                'taxe_id' => 2,
                'estado_tramo' => 'VIGENTE',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'TRAMO 3 (26 a 30 METROS CUBICOS)',
                'inicio' => 26,
                'termino' => 30,
                'neto' => 800,
                'iva'=>0,
                'total'=>800,
                'taxe_id' => 2,
                'estado_tramo' => 'VIGENTE',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'TRAMO 4 (31 a 40 METROS CUBICOS)',
                'inicio' => 31,
                'termino' => 40,
                'neto' => 2480,
                'iva'=>0,
                'total'=>2480,
                'taxe_id' => 2,
                'estado_tramo' => 'VIGENTE',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 5,
                'name' => 'TRAMO 5 (40 METROS CUBICOS o SUPERIORES)',
                'inicio' => 40,
                'termino' => 9999,
                'neto' => 7430,
                'iva'=>0,
                'total'=>7430,
                'taxe_id' => 2,
                'estado_tramo' => 'VIGENTE',
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}
