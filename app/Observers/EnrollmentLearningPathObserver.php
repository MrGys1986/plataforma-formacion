<?php

namespace App\Observers;

use App\Models\Enrollment;
use App\Models\UserLearningPath;
use App\Services\LearningPaths\LearningPathProgressService;

class EnrollmentLearningPathObserver
{
    public function saved(Enrollment $enrollment): void
    {
        if (! $enrollment->wasChanged(['completion_status', 'final_score']) || ! $enrollment->activity_id) {
            return;
        }

        UserLearningPath::query()
            ->where('user_id', $enrollment->user_id)
            ->whereHas('learningPath.items', fn ($query) => $query->where('activity_id', $enrollment->activity_id))
            ->each(fn (UserLearningPath $assignment) => app(LearningPathProgressService::class)
                ->synchronizeAssignment($assignment));
    }
}
