<?php

namespace App\Services\LearningPaths;

use App\Models\LearningPath;
use App\Models\User;

class LearningPathProgressService
{
    public function calculateProgress(User $user, LearningPath $learningPath): float
    {
        $activityIds = $learningPath->items()->pluck('activity_id');

        if ($activityIds->isEmpty()) {
            return 0.0;
        }

        $completed = $user->enrollments()
            ->whereIn('activity_id', $activityIds)
            ->where('completion_status', 'completado')
            ->count();

        return round(($completed / $activityIds->count()) * 100, 2);
    }
}
