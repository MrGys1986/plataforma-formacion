<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $selectedActivity = $request->filled('activity')
            ? Activity::query()->visibleTo($request->user())->findOrFail($request->integer('activity'))
            : null;

        $enrollments = Enrollment::query()
            ->visibleTo($request->user())
            ->when($selectedActivity, fn ($query) => $query->whereBelongsTo($selectedActivity, 'activity'))
            ->with(['user.area', 'activity.activityType', 'activity.area'])
            ->orderByDesc('requested_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('area-manager.enrollments.index', compact('enrollments', 'selectedActivity'));
    }
}
