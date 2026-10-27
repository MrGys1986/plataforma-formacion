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
            ->withCount(['items', 'trainingPrograms', 'diplomaPrograms', 'certificationPrograms'])
            ->with([
                'coverFile',
                'userLearningPaths' => fn ($query) => $query->where('user_id', $request->user()->id),
            ])
            ->paginate(12);

        return view('participant.learning-paths.index', compact('learningPaths'));
    }

    public function show(
        Request $request,
        LearningPath $learningPath,
        LearningPathProgressService $progressService,
    ) {
        $this->authorize('view', $learningPath);
        $learningPath->load([
            'coverFile',
            'trainingPrograms.activityType',
            'trainingPrograms.activities',
            'items.activity.activityType',
        ]);

        $assignment = $learningPath->userLearningPaths()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($assignment) {
            $assignment = $progressService->synchronizeAssignment($assignment);
        }

        $progressPercentage = $assignment
            ? (float) $assignment->progress_percentage
            : $progressService->calculateProgress($request->user(), $learningPath);

        $routeItems = $progressService->itemsFor($learningPath);

        $enrollments = $request->user()
            ->enrollments()
            ->whereIn('activity_id', $routeItems->pluck('activity_id')->filter())
            ->get()
            ->keyBy('activity_id');

        $previousRequiredCompleted = true;
        $items = $routeItems->map(function ($item) use (
            $enrollments,
            $learningPath,
            $progressService,
            &$previousRequiredCompleted,
        ) {
            $enrollment = $enrollments->get($item->activity_id);
            $completed = $enrollment?->completion_status === 'completado'
                && $progressService->meetsMinimumScore(
                    $enrollment->final_score,
                    $item->minimum_score,
                );
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

        return view('participant.learning-paths.show', compact(
            'learningPath',
            'assignment',
            'items',
            'progressPercentage',
        ));
    }
}
