<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
