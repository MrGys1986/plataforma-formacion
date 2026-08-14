<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function index(Request $request)
    {
        $selectedActivity = $request->filled('activity')
            ? Activity::query()->visibleTo($request->user())->findOrFail($request->integer('activity'))
            : null;

        $evidences = Evidence::query()
            ->visibleTo($request->user())
            ->when($selectedActivity, fn ($query) => $query->whereBelongsTo($selectedActivity, 'activity'))
            ->with(['user.area', 'activity.activityType', 'assignedEvaluator', 'enrollment'])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('area-manager.evidences.index', compact('evidences', 'selectedActivity'));
    }
}
