<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Database\Seeder;

class AreaManagerEvidenceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $activity = Activity::query()
            ->where('slug', 'capacitacion-institucional-area-demo')
            ->first();

        if (! $activity) {
            $this->command?->warn('No existe la actividad de demostración del área.');

            return;
        }

        $evaluator = User::query()
            ->role('Evaluador')
            ->where('area_id', $activity->area_id)
            ->first();

        $enrollments = Enrollment::query()
            ->where('activity_id', $activity->id)
            ->where('status', 'aprobada')
            ->with('user')
            ->orderBy('id')
            ->limit(2)
            ->get();

        foreach ($enrollments as $index => $enrollment) {
            $isValidated = $index === 1 && $evaluator;

            Evidence::query()->updateOrCreate(
                [
                    'user_id' => $enrollment->user_id,
                    'activity_id' => $activity->id,
                    'title' => $index === 0
                        ? 'Diagnóstico de necesidades del área'
                        : 'Plan de mejora institucional',
                ],
                [
                    'enrollment_id' => $enrollment->id,
                    'evidence_type' => $index === 0 ? 'proyecto' : 'evaluacion',
                    'description' => $index === 0
                        ? 'Documento de diagnóstico elaborado como parte de la capacitación institucional.'
                        : 'Propuesta de acciones y seguimiento para fortalecer los procesos del área.',
                    'status' => $isValidated ? 'validada' : 'pendiente',
                    'uploaded_by' => $enrollment->user_id,
                    'assigned_evaluator_id' => $evaluator?->id,
                    'validated_by' => $isValidated ? $evaluator->id : null,
                    'validated_at' => $isValidated ? now()->subDay() : null,
                    'rejection_reason' => null,
                ],
            );
        }
    }
}
