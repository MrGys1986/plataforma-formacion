<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            DemoRoleUserSeeder::class,
            AreaManagerEnrollmentDemoSeeder::class,
            AreaManagerEvidenceDemoSeeder::class,
            RubricDemoSeeder::class,
            EvaluatorResultDemoSeeder::class,
            EvaluatorCertificationDemoSeeder::class,
            CompetencyDemoSeeder::class,
        ]);
    }
}
