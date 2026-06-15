<?php

namespace App\Services\Learning;

use App\Models\CertificationProgram;
use App\Models\Competency;
use App\Models\DiplomaProgram;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserLearningPath;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LearningProgressService
{
    public function recalculate(User $user): void
    {
        $completedPrograms = Enrollment::query()
            ->where('user_id', $user->id)
            ->where('completion_status', 'completado')
            ->whereHas('activity', fn ($query) => $query->whereNotNull('training_program_id'))
            ->with('activity:id,training_program_id')
            ->get()
            ->pluck('activity.training_program_id')
            ->filter()
            ->unique()
            ->values();

        $completedDiplomas = $this->updateDiplomas($user, $completedPrograms);
        $completedCertifications = $this->updateCertifications($user, $completedDiplomas);
        $completedCompetencies = $this->updateCompetencies($user, $completedCertifications);
        $this->updateLearningPaths($user, $completedCompetencies);
    }

    private function updateDiplomas(User $user, Collection $completedPrograms): Collection
    {
        $completed = collect();

        DiplomaProgram::query()
            ->where('status', 'activo')
            ->with(['trainingPrograms' => fn ($query) => $query->where('diploma_training_program.is_required', true)])
            ->each(function (DiplomaProgram $diploma) use ($user, $completedPrograms, $completed): void {
                $required = $diploma->trainingPrograms->pluck('id');
                $progress = $this->progress($required, $completedPrograms);
                $status = $this->status($required, $completedPrograms);

                $this->upsertProgress(
                    'user_diploma_programs',
                    ['user_id' => $user->id, 'diploma_program_id' => $diploma->id],
                    $status,
                    $progress,
                );

                if ($status === 'completado') {
                    $completed->push($diploma->id);
                }
            });

        return $completed;
    }

    private function updateCertifications(User $user, Collection $completedDiplomas): Collection
    {
        $completed = collect();

        CertificationProgram::query()
            ->where('status', 'activo')
            ->with(['diplomaPrograms' => fn ($query) => $query->where('certification_diploma.is_required', true)])
            ->each(function (CertificationProgram $certification) use ($user, $completedDiplomas, $completed): void {
                $required = $certification->diplomaPrograms->pluck('id');
                $progress = $this->progress($required, $completedDiplomas);
                $status = $this->status($required, $completedDiplomas);

                $this->upsertProgress(
                    'user_certification_programs',
                    ['user_id' => $user->id, 'certification_program_id' => $certification->id],
                    $status,
                    $progress,
                );

                if ($status === 'completado') {
                    $completed->push($certification->id);
                }
            });

        return $completed;
    }

    private function updateCompetencies(User $user, Collection $completedCertifications): Collection
    {
        $completed = collect();

        Competency::query()
            ->where('status', 'activo')
            ->with(['certificationPrograms' => fn ($query) => $query->where('competency_certification.is_required', true)])
            ->each(function (Competency $competency) use ($user, $completedCertifications, $completed): void {
                $required = $competency->certificationPrograms->pluck('id');
                $progress = $this->progress($required, $completedCertifications);
                $status = $this->status($required, $completedCertifications);

                $this->upsertProgress(
                    'user_competencies',
                    ['user_id' => $user->id, 'competency_id' => $competency->id],
                    $status,
                    $progress,
                );

                if ($status === 'completado') {
                    $completed->push($competency->id);
                }
            });

        return $completed;
    }

    private function updateLearningPaths(User $user, Collection $completedCompetencies): void
    {
        UserLearningPath::query()
            ->where('user_id', $user->id)
            ->each(function (UserLearningPath $assignment) use ($completedCompetencies): void {
                $learningPath = $assignment->learningPath()
                    ->with(['competencyDefinitions' => fn ($query) => $query->where('learning_path_competency.is_required', true)])
                    ->first();

                if (! $learningPath) {
                    return;
                }

                $required = $learningPath->competencyDefinitions->pluck('id');
                $progress = $this->progress($required, $completedCompetencies);
                $status = $this->status($required, $completedCompetencies);

                $assignment->update([
                    'status' => match ($status) {
                        'completado' => 'completada',
                        'en_progreso' => 'en_progreso',
                        default => 'no_iniciada',
                    },
                    'progress_percentage' => $progress,
                    'started_at' => $progress > 0 ? ($assignment->started_at ?? now()) : $assignment->started_at,
                    'completed_at' => $status === 'completado' ? ($assignment->completed_at ?? now()) : null,
                ]);
            });
    }

    private function progress(Collection $required, Collection $completed): float
    {
        if ($required->isEmpty()) {
            return 0;
        }

        return round($required->intersect($completed)->count() * 100 / $required->count(), 2);
    }

    private function status(Collection $required, Collection $completed): string
    {
        if ($required->isEmpty()) {
            return 'bloqueado';
        }

        $completedCount = $required->intersect($completed)->count();

        return match (true) {
            $completedCount === $required->count() => 'completado',
            $completedCount > 0 => 'en_progreso',
            default => 'bloqueado',
        };
    }

    private function upsertProgress(string $table, array $keys, string $status, float $progress): void
    {
        $existing = DB::table($table)->where($keys)->first();

        DB::table($table)->updateOrInsert($keys, [
            'status' => $status,
            'progress_percentage' => $progress,
            'unlocked_at' => $progress > 0 ? ($existing?->unlocked_at ?? now()) : null,
            'completed_at' => $status === 'completado' ? ($existing?->completed_at ?? now()) : null,
            'created_at' => $existing?->created_at ?? now(),
            'updated_at' => now(),
        ]);
    }
}
