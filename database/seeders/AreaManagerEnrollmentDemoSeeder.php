<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class AreaManagerEnrollmentDemoSeeder extends Seeder
{
    public function run(): void
    {
        $areaManager = User::query()
            ->role('Responsable Area')
            ->whereNotNull('area_id')
            ->first();

        if (! $areaManager) {
            $this->command?->warn('No hay un Responsable de área con área asignada.');

            return;
        }

        $activityType = ActivityType::query()->where('name', 'Curso')->first()
            ?? ActivityType::query()->first();

        if (! $activityType) {
            $this->command?->warn('No hay tipos de actividad disponibles.');

            return;
        }

        $instructor = User::query()
            ->where('area_id', $areaManager->area_id)
            ->role('Personal')
            ->first() ?? $areaManager;

        $activity = Activity::query()->updateOrCreate(
            ['slug' => 'capacitacion-institucional-area-demo'],
            [
                'activity_type_id' => $activityType->id,
                'area_id' => $areaManager->area_id,
                'instructor_id' => $instructor->id,
                'created_by' => $areaManager->id,
                'name' => 'Capacitación institucional del área',
                'description' => 'Actividad de demostración para validar las inscripciones y el seguimiento del área.',
                'modality' => 'virtual',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(21)->toDateString(),
                'duration_hours' => 20,
                'min_capacity' => 3,
                'max_capacity' => 25,
                'cost' => 0,
                'is_external' => false,
                'requires_approval' => true,
                'requires_payment' => false,
                'requires_evaluation' => true,
                'status' => 'en_curso',
            ],
        );

        $participants = User::query()
            ->where('area_id', $areaManager->area_id)
            ->whereIn('email', [
                'ana.participante@formacion.test',
                'carlos.participante@formacion.test',
                'mariana.participante@formacion.test',
            ])
            ->get();

        foreach ($participants as $index => $participant) {
            Enrollment::query()->updateOrCreate(
                [
                    'user_id' => $participant->id,
                    'activity_id' => $activity->id,
                ],
                [
                    'status' => $index === 2 ? 'solicitada' : 'aprobada',
                    'requested_at' => now()->subDays(5 - $index),
                    'approved_by' => $index === 2 ? null : $areaManager->id,
                    'approved_at' => $index === 2 ? null : now()->subDays(3 - $index),
                    'payment_status' => 'no_aplica',
                    'completion_status' => 'no_iniciado',
                    'final_score' => null,
                    'completed_at' => null,
                ],
            );
        }
    }
}
