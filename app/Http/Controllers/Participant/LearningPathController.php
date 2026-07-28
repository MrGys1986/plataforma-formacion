<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\LearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public function index(Request $request)
    {
        $learningPaths = LearningPath::query()
            ->visibleTo($request->user())
            ->withCount('items')
            ->with(['userLearningPaths' => fn ($query) => $query->where('user_id', $request->user()->id)])
            ->paginate(12);

        return view('participant.learning-paths.index', compact('learningPaths'));
    }

    public function show(
        Request $request,
        LearningPath $learningPath,
        LearningPathProgressService $progressService,
    ) {
        $this->authorize('view', $learningPath);
        $learningPath->load('items.activity');

        $assignment = $learningPath->userLearningPaths()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($assignment) {
            $assignment = $progressService->synchronizeAssignment($assignment);
        }

        $enrollments = $request->user()
            ->enrollments()
            ->whereIn('activity_id', $learningPath->items->pluck('activity_id'))
            ->get()
            ->keyBy('activity_id');

        $previousRequiredCompleted = true;
        $items = $learningPath->items->map(function ($item) use (
            $enrollments,
            $learningPath,
            &$previousRequiredCompleted,
        ) {
            $enrollment = $enrollments->get($item->activity_id);
            $completed = $enrollment?->completion_status === 'completado'
                && ($item->minimum_score === null
                    || (float) $enrollment->final_score >= (float) $item->minimum_score);
            $unlocked = ! $learningPath->is_sequential || $previousRequiredCompleted;

            if ($item->is_required && ! $completed) {
                $previousRequiredCompleted = false;
            }

            return (object) [
                'item' => $item,
                'enrollment' => $enrollment,
                'completed' => $completed,
                'unlocked' => $unlocked,
            ];
        });

        return view('participant.learning-paths.show', compact('learningPath', 'assignment', 'items'));
    }
}
