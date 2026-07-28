<?php

namespace App\Services\LearningPaths;

use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\User;
use App\Models\UserLearningPath;

class LearningPathProgressService
{
    public function calculateProgress(User $user, LearningPath $learningPath): float
    {
        $requiredItems = $learningPath->items()
            ->where('is_required', true)
            ->get(['activity_id', 'minimum_score']);

        if ($requiredItems->isEmpty()) {
            return 0.0;
        }

        $enrollments = $user->enrollments()
            ->whereIn('activity_id', $requiredItems->pluck('activity_id'))
            ->where('completion_status', 'completado')
            ->get()
            ->keyBy('activity_id');

        $completed = $requiredItems->filter(function ($item) use ($enrollments): bool {
            $enrollment = $enrollments->get($item->activity_id);

            return $enrollment
                && ($item->minimum_score === null
                    || (float) $enrollment->final_score >= (float) $item->minimum_score);
        })->count();

        return round(($completed / $requiredItems->count()) * 100, 2);
    }

    public function synchronizeAssignment(UserLearningPath $assignment): UserLearningPath
    {
        $assignment->loadMissing(['user', 'learningPath.items']);

        foreach ($assignment->learningPath->items as $item) {
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

        return $assignment->refresh();
    }
}
