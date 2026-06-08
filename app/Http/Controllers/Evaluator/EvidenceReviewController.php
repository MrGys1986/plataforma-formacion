<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceReviewController extends Controller
{
    public function index(Request $request)
    {
        $evidences = Evidence::query()
            ->visibleTo($request->user())
            ->where('status', 'pendiente')
            ->with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('evaluator.evidences.index', compact('evidences'));
    }

    public function show(Evidence $evidence)
    {
        $this->authorize('view', $evidence);
        $evidence->load(['user', 'activity', 'fileUpload', 'reviews.reviewedBy']);

        return view('evaluator.evidences.show', compact('evidence'));
    }
}
