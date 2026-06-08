<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function show(Survey $survey, Activity $activity)
    {
        $this->authorize('respond', [$survey, $activity]);
        $survey->load('questions');

        return view('participant.surveys.show', compact('survey', 'activity'));
    }
}
