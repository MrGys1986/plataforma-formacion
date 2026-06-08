<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Minicurso',
            'Taller',
            'Curso',
            'Diplomado',
            'Certificacion',
            'Competencia',
        ] as $name) {
            ActivityType::updateOrCreate(
                ['name' => $name],
                [
                    'default_generates_certificate' => true,
                    'default_generates_microcredential' => false,
                    'status' => 'activo',
                ],
            );
        }
    }
}
