<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoRoleUserSeeder extends Seeder
{
    public const DEFAULT_PASSWORD = 'Formacion2026!';

    public function run(): void
    {
        $defaultAreaId = Area::query()->value('id');

        $accounts = [
            [
                'name' => 'Superadministrador de prueba',
                'email' => 'admin@formacion.test',
                'role' => 'Superadministrador',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Personal de prueba',
                'email' => 'personal@formacion.test',
                'role' => 'Personal',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Responsable de área de prueba',
                'email' => 'responsable.area@formacion.test',
                'role' => 'Responsable Area',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Evaluador de prueba',
                'email' => 'evaluador@formacion.test',
                'role' => 'Evaluador',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Recursos Humanos de prueba',
                'email' => 'rh@formacion.test',
                'role' => 'Recursos Humanos',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Calidad Académica de prueba',
                'email' => 'calidad@formacion.test',
                'role' => 'Calidad Academica',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Educación Continua de prueba',
                'email' => 'educacion.continua@formacion.test',
                'role' => 'Educacion Continua',
                'area_id' => $defaultAreaId,
            ],
            [
                'name' => 'Participante externo de prueba',
                'email' => 'externo@formacion.test',
                'role' => 'Externo',
                'area_id' => null,
                'user_type' => 'externo',
                'profile_type' => 'externo',
                'external_institution' => 'Institución externa de prueba',
            ],
            [
                'name' => 'Profesor de prueba',
                'email' => 'profesor@formacion.test',
                'role' => 'Profesor',
                'area_id' => $defaultAreaId,
                'user_type' => 'interno',
                'profile_type' => 'profesor',
            ],
            [
                'name' => 'Alumno de prueba',
                'email' => 'alumno@formacion.test',
                'role' => 'Alumno',
                'area_id' => $defaultAreaId,
                'user_type' => 'interno',
                'profile_type' => 'alumno',
            ],
            [
                'name' => 'Ana Martínez López',
                'email' => 'ana.participante@formacion.test',
                'role' => 'Alumno',
                'area_id' => $defaultAreaId,
                'user_type' => 'interno',
                'profile_type' => 'alumno',
            ],
            [
                'name' => 'Carlos Hernández Ruiz',
                'email' => 'carlos.participante@formacion.test',
                'role' => 'Alumno',
                'area_id' => $defaultAreaId,
                'user_type' => 'interno',
                'profile_type' => 'alumno',
            ],
            [
                'name' => 'Mariana Torres García',
                'email' => 'mariana.participante@formacion.test',
                'role' => 'Alumno',
                'area_id' => $defaultAreaId,
                'user_type' => 'interno',
                'profile_type' => 'alumno',
            ],
        ];

        foreach ($accounts as $account) {
            $roleName = $account['role'];
            Role::findOrCreate($roleName, 'web');
            unset($account['role']);

            $user = User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, [
                    'password' => Hash::make(self::DEFAULT_PASSWORD),
                    'status' => 'activo',
                ]),
            );

            $user->syncRoles([$roleName]);
        }
    }
}
