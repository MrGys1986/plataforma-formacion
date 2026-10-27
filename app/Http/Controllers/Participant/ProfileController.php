<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $completedQuery = $user->enrollments()
            ->where('status', 'aprobada')
            ->where('completion_status', 'completado');
        $completedEnrollments = (clone $completedQuery)
            ->with([
                'activity.activityType',
                'activity.coverFile',
                'activity.trainingProgram.coverFile',
                'certificates' => fn ($query) => $query->where('status', 'emitida')->with('fileUpload'),
            ])
            ->latest('completed_at')->latest('id')->paginate(12);
        $completedHours = (float) Activity::query()->whereHas('enrollments', fn ($query) => $query
            ->where('user_id', $user->id)->where('status', 'aprobada')->where('completion_status', 'completado'))
            ->sum('duration_hours');
        $availableCertificates = Certificate::query()->where('user_id', $user->id)->where('status', 'emitida')
            ->whereHas('fileUpload')
            ->whereHas('enrollment', fn ($query) => $query->where('user_id', $user->id)
                ->where('status', 'aprobada')->where('completion_status', 'completado'))
            ->count();

        return view('participant.profile.index', compact('completedEnrollments', 'completedHours', 'availableCertificates'));
    }
}
