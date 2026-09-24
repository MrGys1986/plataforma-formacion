<?php

namespace App\Services\LearningPaths;

use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\Microcredentials\LearningPathBadgeService;
use App\Services\Training\TrainingProgramOperationService;
use Illuminate\Support\Collection;

class LearningPathProgressService
{
    public function calculateProgress(User $user, LearningPath $learningPath): float
    {
        $requiredItems = $this->itemsFor($learningPath)
            ->where('is_required', true);

        if ($requiredItems->isEmpty()) {
            return 0.0;
        }

        $enrollments = $user->enrollments()
            ->whereIn('activity_id', $requiredItems->pluck('activity_id')->filter())
            ->where('completion_status', 'completado')
            ->get()
            ->keyBy('activity_id');

        $completed = $requiredItems->filter(function ($item) use ($enrollments): bool {
            $enrollment = $enrollments->get($item->activity_id);

            return $enrollment
                && $this->meetsMinimumScore($enrollment->final_score, $item->minimum_score);
        })->count();

        return round(($completed / $requiredItems->count()) * 100, 2);
    }

    public function meetsMinimumScore(float|string|null $score, float|string|null $minimumScore): bool
    {
        if ($minimumScore === null) {
            return true;
        }

        if ($score === null) {
            return false;
        }

        $score = (float) $score;
        $minimumScore = (float) $minimumScore;

        // Permite capturar 9/10 cuando la ruta expresa el mínimo como 80/100.
        if ($score <= 10 && $minimumScore > 10) {
            $score *= 10;
        }

        return $score >= $minimumScore;
    }

    public function synchronizeAssignment(UserLearningPath $assignment): UserLearningPath
    {
        $assignment->loadMissing(['user', 'learningPath']);

        $items = $this->itemsFor($assignment->learningPath, ensureActivities: true);

        foreach ($items->whereNotNull('activity_id') as $item) {
            Enrollment::firstOrCreate(
                [
                    'user_id' => $assignment->user_id,
                    'activity_id' => $item->activity_id,
                ],
                [
                    'status' => 'aprobada',
                    'requested_at' => now(),
                    'approved_at' => now(),
                    'payment_status' => 'no_aplica',
                    'completion_status' => 'no_iniciado',
                ],
            );
        }

        $progress = $this->calculateProgress($assignment->user, $assignment->learningPath);

        $assignment->forceFill([
            'progress_percentage' => $progress,
            'status' => match (true) {
                $progress >= 100 => 'completada',
                $progress > 0 => 'en_progreso',
                default => 'no_iniciada',
            },
            'started_at' => $progress > 0 ? ($assignment->started_at ?? now()) : null,
            'completed_at' => $progress >= 100 ? ($assignment->completed_at ?? now()) : null,
        ])->saveQuietly();

        $assignment = $assignment->refresh();
        app(LearningPathBadgeService::class)->issueIfCompleted($assignment);

        return $assignment;
    }

    /**
     * Returns both the current program-based structure and any legacy activities
     * that have not yet been associated with a program.
     *
     * @return Collection<int, object>
     */
    public function itemsFor(LearningPath $learningPath, bool $ensureActivities = false): Collection
    {
        $learningPath->loadMissing([
            'trainingPrograms.activityType',
            'trainingPrograms.activities',
            'items.activity.activityType',
            'items.activity.trainingProgram',
        ]);

        $programIds = $learningPath->trainingPrograms->pluck('id');

        $programItems = $learningPath->trainingPrograms
            ->map(function ($program) use ($ensureActivities): object {
                $activity = $ensureActivities
                    ? app(TrainingProgramOperationService::class)->resolve($program)
                    : $program->activities->sortByDesc('id')->first();

                if ($activity && $ensureActivities && ! $program->activities->contains('id', $activity->id)) {
                    $program->setRelation('activities', $program->activities->push($activity));
                }

                return (object) [
                    'activity_id' => $activity?->id,
                    'activity' => $activity,
                    'trainingProgram' => $program,
                    'order_number' => (int) $program->pivot->order_number,
                    'is_required' => (bool) $program->pivot->is_required,
                    'minimum_score' => $program->pivot->minimum_score,
                ];
            });

        $legacyItems = $learningPath->items
            ->reject(fn ($item): bool => $item->activity?->training_program_id
                && $programIds->contains($item->activity->training_program_id))
            ->map(fn ($item): object => (object) [
                'activity_id' => $item->activity_id,
                'activity' => $item->activity,
                'trainingProgram' => $item->activity?->trainingProgram,
                'order_number' => (int) $item->order_number,
                'is_required' => (bool) $item->is_required,
                'minimum_score' => $item->minimum_score,
            ]);

        return $programItems
            ->concat($legacyItems)
            ->sortBy('order_number')
            ->values();
    }
}
