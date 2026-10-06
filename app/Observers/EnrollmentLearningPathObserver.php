<?php

namespace App\Observers;

use App\Models\Enrollment;
use App\Services\Microcredentials\LearningPathBadgeService;

class EnrollmentLearningPathObserver
{
    public function updated(Enrollment $enrollment): void
    {
        if (! $enrollment->wasChanged(['completion_status', 'final_score'])
            || $enrollment->completion_status !== 'completado'
            || ! $enrollment->activity_id) {
            return;
        }

        $enrollment->loadMissing(['activity', 'user']);

        if ($enrollment->activity && $enrollment->user) {
            app(LearningPathBadgeService::class)->issue(
                $enrollment->user,
                $enrollment->activity,
                $enrollment->completed_at,
            );
        }
    }
}
