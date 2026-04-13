<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

use App\Models\City;
use Carbon\Carbon;

class ChileCitiesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $cities = [

            // Región Arica y Parinacota
            ['Arica', 1],

            // Tarapacá
            ['Iquique', 3],

            // Antofagasta
            ['Antofagasta', 5],
            ['Calama', 6],

            // Atacama
            ['Copiapó', 8],
            ['Vallenar', 10],

            // Coquimbo
            ['La Serena', 11],
            ['Coquimbo', 11],
            ['Ovalle', 13],

            // Valparaíso
            ['Valparaíso', 14],
            ['Viña del Mar', 14],
            ['San Antonio', 19],
            ['Quillota', 18],
            ['Los Andes', 16],

            // Metropolitana
            ['Santiago', 22],
            ['Puente Alto', 23],
            ['San Bernardo', 25],
            ['Melipilla', 26],

            // O’Higgins
            ['Rancagua', 28],
            ['San Fernando', 30],
            ['Pichilemu', 29],

            // Maule
            ['Talca', 31],
            ['Curicó', 33],
            ['Linares', 34],
            ['Cauquenes', 32],

            // Ñuble
            ['Chillán', 35],
            ['San Carlos', 37],

            // Biobío
            ['Concepción', 38],
            ['Talcahuano', 38],
            ['Los Ángeles', 40],

            // La Araucanía
            ['Temuco', 41],
            ['Villarrica', 41],
            ['Angol', 42],

            // Los Ríos
            ['Valdivia', 43],
            ['La Unión', 44],

            // Los Lagos
            ['Puerto Montt', 45],
            ['Osorno', 47],
            ['Castro', 46],
            ['Puerto Varas', 45],

            // Aysén
            ['Coyhaique', 49],

            // Magallanes
            ['Punta Arenas', 53],
            ['Puerto Natales', 56],
        ];

        $cities = array_map(function ($city) use ($now) {
            return [
                'name' => $city[0],
                'province_id' => $city[1],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $cities);

        City::insert($cities);
    }
}

