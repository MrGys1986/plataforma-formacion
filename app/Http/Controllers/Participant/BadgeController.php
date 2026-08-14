<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Microcredential;
use App\Services\Microcredentials\LearningPathBadgeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(Request $request, LearningPathBadgeService $badgeService): View
    {
        $request->user()
            ->userLearningPaths()
            ->where('status', 'completada')
            ->with(['user', 'learningPath'])
            ->each(fn ($assignment) => $badgeService->issueIfCompleted($assignment));

        $badges = $request->user()
            ->microcredentials()
            ->whereNotNull('learning_path_id')
            ->where('status', 'validada')
            ->with('learningPath')
            ->latest('issued_at')
            ->paginate(12);

        return view('participant.badges.index', compact('badges'));
    }

    public function show(Request $request, Microcredential $microcredential): View
    {
        abort_unless($microcredential->user_id === $request->user()->id, 403);

        abort_unless($microcredential->learning_path_id !== null, 404);

        $microcredential->load(['learningPath', 'user']);

        return view('participant.badges.show', compact('microcredential'));
    }
}
