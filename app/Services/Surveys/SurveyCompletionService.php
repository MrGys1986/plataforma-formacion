<?php

namespace App\Services\Surveys;

use App\Models\Activity;
use App\Models\SurveyResponse;
use App\Models\User;

class SurveyCompletionService
{
    public function hasCompletedSurvey(User $user, Activity $activity): bool
    {
        return SurveyResponse::query()
            ->where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->exists();
    }
}
