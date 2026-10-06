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
            ->where('status', 'validada')
            ->with(['learningPath', 'diplomaProgram', 'certificationProgram', 'activity.trainingProgram'])
            ->latest('issued_at')
            ->paginate(12);

        return view('participant.badges.index', compact('badges'));
    }

    public function show(Request $request, Microcredential $microcredential): View
    {
        abort_unless($microcredential->user_id === $request->user()->id, 403);

        $microcredential->load(['learningPath', 'diplomaProgram', 'certificationProgram', 'activity', 'user']);

        return view('participant.badges.show', compact('microcredential'));
    }
}
