<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Recursos Humanos', 'area_type' => 'administrativa'],
            ['name' => 'Calidad Académica', 'area_type' => 'académica'],
            ['name' => 'Educación Continua', 'area_type' => 'administrativa'],
            ['name' => 'Sistemas', 'area_type' => 'administrativa'],
        ] as $area) {
            Area::updateOrCreate(
                ['name' => $area['name']],
                $area + ['status' => 'activa'],
            );
        }
    }
}
