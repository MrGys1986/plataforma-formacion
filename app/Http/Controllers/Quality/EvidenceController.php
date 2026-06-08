<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function index(Request $request)
    {
        $evidences = Evidence::query()
            ->visibleTo($request->user())
            ->with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('quality.evidences.index', compact('evidences'));
    }
}
