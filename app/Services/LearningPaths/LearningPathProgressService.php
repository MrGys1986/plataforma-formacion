<?php

namespace App\Services\LearningPaths;

use App\Models\Enrollment;
use App\Models\CertificationProgram;
use App\Models\DiplomaProgram;
use App\Models\Activity;
use App\Models\LearningPath;
use App\Models\TrainingProgram;
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
        $badges = app(LearningPathBadgeService::class);

        foreach ($assignment->learningPath->diplomaPrograms as $diploma) {
            if ($this->completed($assignment->user, $this->itemsForDiploma($diploma, true))) {
                $badges->issue($assignment->user, $diploma);
            }
        }

        foreach ($assignment->learningPath->certificationPrograms as $certification) {
            foreach ($certification->diplomaPrograms as $diploma) {
                if ($this->completed($assignment->user, $this->itemsForDiploma($diploma, true))) {
                    $badges->issue($assignment->user, $diploma);
                }
            }

            if ($this->completed($assignment->user, $this->itemsForCertification($certification, true))) {
                $badges->issue($assignment->user, $certification);
            }
        }

        $badges->issueIfCompleted($assignment);

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
            'diplomaPrograms.trainingPrograms.activities',
            'certificationPrograms.trainingPrograms.activities',
            'certificationPrograms.diplomaPrograms.trainingPrograms.activities',
            'items.activity.activityType',
            'items.activity.trainingProgram',
        ]);

        $programItems = $this->programItems($learningPath->trainingPrograms, $ensureActivities, 'Ruta')
            ->map(function (object $item): object {
                $item->order_number *= 1000000;
                return $item;
            });

        foreach ($learningPath->diplomaPrograms as $diploma) {
            $programItems = $programItems->concat($this->itemsForDiploma($diploma, $ensureActivities)
                ->map(function (object $item) use ($diploma): object {
                    $item->is_required = (bool) $diploma->pivot->is_required && $item->is_required;
                    $item->order_number = (int) $diploma->pivot->order_number * 1000000 + $item->order_number;
                    return $item;
                }));
        }

        foreach ($learningPath->certificationPrograms as $certification) {
            $programItems = $programItems->concat($this->itemsForCertification($certification, $ensureActivities)
                ->map(function (object $item) use ($certification): object {
                    $item->is_required = (bool) $certification->pivot->is_required && $item->is_required;
                    $item->order_number = (int) $certification->pivot->order_number * 1000000 + $item->order_number;
                    return $item;
                }));
        }

        $programIds = $programItems->pluck('trainingProgram.id')->filter()->unique();

        $legacyItems = $learningPath->items
            ->reject(fn ($item): bool => $item->activity?->training_program_id
                && $programIds->contains($item->activity->training_program_id))
            ->map(fn ($item): object => (object) [
                'activity_id' => $item->activity_id,
                'activity' => $item->activity,
                'trainingProgram' => $item->activity?->trainingProgram,
                'order_number' => (int) $item->order_number * 1000000,
                'is_required' => (bool) $item->is_required,
                'minimum_score' => $item->minimum_score,
                'group_name' => 'Actividad anterior',
            ]);

        return $programItems
            ->concat($legacyItems)
            ->sortBy('order_number')
            ->pipe(fn (Collection $items): Collection => $this->consolidate($items));
    }

    public function itemsForDiploma(DiplomaProgram $diploma, bool $ensureActivities = false): Collection
    {
        $diploma->loadMissing('trainingPrograms.activities');

        return $this->programItems($diploma->trainingPrograms, $ensureActivities, $diploma->name);
    }

    public function itemsForCertification(CertificationProgram $certification, bool $ensureActivities = false): Collection
    {
        $certification->loadMissing(['trainingPrograms.activities', 'diplomaPrograms.trainingPrograms.activities']);
        $items = $this->programItems($certification->trainingPrograms, $ensureActivities, $certification->name)
            ->map(function (object $item): object {
                $item->order_number *= 1000;
                return $item;
            });

        foreach ($certification->diplomaPrograms as $diploma) {
            $items = $items->concat($this->itemsForDiploma($diploma, $ensureActivities)
                ->map(function (object $item) use ($diploma, $certification): object {
                    $item->is_required = (bool) $diploma->pivot->is_required && $item->is_required;
                    $item->order_number = (int) $diploma->pivot->order_number * 1000 + $item->order_number;
                    $item->group_name = $certification->name.' / '.$diploma->name;
                    return $item;
                }));
        }

        return $this->consolidate($items);
    }

    public function completed(User $user, Collection $items): bool
    {
        $required = $items->where('is_required', true)->whereNotNull('activity_id');

        if ($required->isEmpty()) {
            return false;
        }

        $enrollments = $user->enrollments()
            ->whereIn('activity_id', $required->pluck('activity_id'))
            ->where('completion_status', 'completado')
            ->get()
            ->keyBy('activity_id');

        return $required->every(fn (object $item): bool => ($enrollment = $enrollments->get($item->activity_id))
            && $this->meetsMinimumScore($enrollment->final_score, $item->minimum_score));
    }

    public function canAccessActivity(User $user, Activity $activity): bool
    {
        $assignments = $user->userLearningPaths()
            ->whereHas('learningPath', fn ($query) => $query->where('is_sequential', true))
            ->with('learningPath')
            ->get();

        foreach ($assignments as $assignment) {
            $items = $this->itemsFor($assignment->learningPath);
            $position = $items->search(fn (object $item): bool => $item->activity_id === $activity->id);

            if ($position === false) {
                continue;
            }

            $previous = $items->take($position)->where('is_required', true);

            if ($previous->isNotEmpty() && ! $this->completed($user, $previous)) {
                return false;
            }
        }

        return true;
    }

    private function programItems(Collection $programs, bool $ensureActivities, string $groupName): Collection
    {
        return $programs->map(function (TrainingProgram $program) use ($ensureActivities, $groupName): object {
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
                'group_name' => $groupName,
            ];
        });
    }

    private function consolidate(Collection $items): Collection
    {
        return $items->groupBy(fn (object $item): string => $item->activity_id
            ? 'activity:'.$item->activity_id
            : 'program:'.$item->trainingProgram?->id)
            ->map(function (Collection $duplicates): object {
                $item = clone $duplicates->first();
                $required = $duplicates->where('is_required', true);
                $item->is_required = $required->isNotEmpty();
                $minimumScores = $required->pluck('minimum_score')->filter(fn ($score): bool => $score !== null);
                $item->minimum_score = $minimumScores->isEmpty() ? null : $minimumScores->max();

                return $item;
            })
            ->values();
    }
}
