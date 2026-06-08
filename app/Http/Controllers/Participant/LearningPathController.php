<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\LearningPath;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public function index(Request $request)
    {
        $learningPaths = LearningPath::query()
            ->visibleTo($request->user())
            ->withCount('items')
            ->paginate(12);

        return view('participant.learning-paths.index', compact('learningPaths'));
    }

    public function show(LearningPath $learningPath)
    {
        $this->authorize('view', $learningPath);
        $learningPath->load('items.activity');

        return view('participant.learning-paths.show', compact('learningPath'));
    }
}
