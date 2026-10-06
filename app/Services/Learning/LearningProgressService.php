<?php

namespace App\Services\Learning;

use App\Models\CertificationProgram;
use App\Models\DiplomaProgram;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use App\Services\Microcredentials\LearningPathBadgeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LearningProgressService
{
    public function recalculate(User $user): void
    {
        $completedDiplomas = $this->updateDiplomas($user);
        $this->updateCertifications($user, $completedDiplomas);
        $this->updateLearningPaths($user);
    }

    private function updateDiplomas(User $user): Collection
    {
        $completed = collect();

        DiplomaProgram::query()
            ->where('status', 'activo')
            ->with(['trainingPrograms' => fn ($query) => $query->where('diploma_training_program.is_required', true)])
            ->each(function (DiplomaProgram $diploma) use ($user, $completed): void {
                $required = $diploma->trainingPrograms->pluck('id');
                $qualified = $this->qualifiedPrograms($user, $diploma->trainingPrograms);
                $progress = $this->progress($required, $qualified);
                $status = $this->status($required, $qualified);

                $this->upsertProgress(
                    'user_diploma_programs',
                    ['user_id' => $user->id, 'diploma_program_id' => $diploma->id],
                    $status,
                    $progress,
                );

                if ($status === 'completado') {
                    $completed->push($diploma->id);
                    app(LearningPathBadgeService::class)->issue($user, $diploma);
                }
            });

        return $completed;
    }

    private function updateCertifications(User $user, Collection $completedDiplomas): Collection
    {
        $completed = collect();

        CertificationProgram::query()
            ->where('status', 'activo')
            ->with([
                'diplomaPrograms' => fn ($query) => $query->where('certification_diploma.is_required', true),
                'trainingPrograms' => fn ($query) => $query->where('certification_training_program.is_required', true),
            ])
            ->each(function (CertificationProgram $certification) use ($user, $completedDiplomas, $completed): void {
                $requiredDiplomas = $certification->diplomaPrograms->pluck('id');
                $requiredPrograms = $certification->trainingPrograms->pluck('id');
                $qualifiedPrograms = $this->qualifiedPrograms($user, $certification->trainingPrograms);
                $required = $requiredDiplomas->map(fn ($id) => 'd:'.$id)
                    ->concat($requiredPrograms->map(fn ($id) => 'p:'.$id));
                $qualified = $completedDiplomas->map(fn ($id) => 'd:'.$id)
                    ->concat($qualifiedPrograms->map(fn ($id) => 'p:'.$id));
                $progress = $this->progress($required, $qualified);
                $status = $this->status($required, $qualified);

                $this->upsertProgress(
                    'user_certification_programs',
                    ['user_id' => $user->id, 'certification_program_id' => $certification->id],
                    $status,
                    $progress,
                );

                if ($status === 'completado') {
                    $completed->push($certification->id);
                    app(LearningPathBadgeService::class)->issue($user, $certification);
                }
            });

        return $completed;
    }

    private function updateLearningPaths(User $user): void
    {
        UserLearningPath::query()
            ->where('user_id', $user->id)
            ->each(function (UserLearningPath $assignment): void {
                app(LearningPathProgressService::class)->synchronizeAssignment($assignment);
            });
    }

    private function progress(Collection $required, Collection $completed): float
    {
        if ($required->isEmpty()) {
            return 0;
        }

        return round($required->intersect($completed)->count() * 100 / $required->count(), 2);
    }

    private function qualifiedPrograms(User $user, Collection $programs): Collection
    {
        if ($programs->isEmpty()) {
            return collect();
        }

        $enrollments = Enrollment::query()
            ->where('user_id', $user->id)
            ->where('completion_status', 'completado')
            ->whereHas('activity', fn ($query) => $query->whereIn('training_program_id', $programs->pluck('id')))
            ->with('activity:id,training_program_id')
            ->get();

        return $programs->filter(fn ($program): bool => $enrollments->contains(fn ($enrollment): bool =>
            $enrollment->activity?->training_program_id === $program->id
            && app(LearningPathProgressService::class)->meetsMinimumScore(
                $enrollment->final_score,
                $program->pivot->minimum_score,
            )
        ))->pluck('id');
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
