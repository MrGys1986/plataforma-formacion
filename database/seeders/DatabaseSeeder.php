<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AreaSeeder::class,
            ActivityTypeSeeder::class,
            SurveySeeder::class,
            LearningPathDemoSeeder::class,
        ]);

        $administrator = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Administrador de prueba',
                'password' => Hash::make('password'),
                'status' => 'activo',
            ],
        );

        if (! $administrator->hasRole('Superadministrador')) {
            $administrator->assignRole('Superadministrador');
        }
    }
}
