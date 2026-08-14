<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->with('activityType')
            ->paginate(12);

        return view('personal.courses.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $this->authorize('view', $activity);
        $activity
            ->load(['activityType', 'area'])
            ->loadCount([
                'enrollments',
                'enrollments as approved_enrollments_count' => fn ($query) => $query->where('status', 'aprobada'),
                'evidences',
                'evidences as pending_evidences_count' => fn ($query) => $query->where('status', 'pendiente'),
                'evaluations',
                'certificates',
            ]);

        $attendanceRecordsCount = AttendanceRecord::query()
            ->whereHas('enrollment', fn ($query) => $query->where('activity_id', $activity->id))
            ->count();

        return view('personal.courses.show', compact('activity', 'attendanceRecordsCount'));
    }

    public function participants(Activity $activity)
    {
        $this->authorize('view', $activity);
        $enrollments = $activity->enrollments()->with('user')->paginate(20);

        return view('personal.courses.participants', compact('activity', 'enrollments'));
    }
}
