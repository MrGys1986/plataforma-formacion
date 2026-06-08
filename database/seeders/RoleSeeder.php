<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Superadministrador',
            'Recursos Humanos',
            'Calidad Academica',
            'Educacion Continua',
            'Instructor',
            'Evaluador',
            'Profesor',
            'Alumno',
            'Externo',
            'Responsable Area',
        ] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }
}
