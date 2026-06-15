<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class AttendanceController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('view', $activity);
        $attendanceRecords = $activity->enrollments()
            ->with(['user', 'attendanceRecords'])
            ->paginate(20);

        return view('personal.attendance.index', compact('activity', 'attendanceRecords'));
    }
}
