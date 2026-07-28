<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Microcredential;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(Request $request): View
    {
        $badges = $request->user()
            ->microcredentials()
            ->where('status', 'validada')
            ->with('activity')
            ->latest('issued_at')
            ->paginate(12);

        return view('participant.badges.index', compact('badges'));
    }

    public function show(Request $request, Microcredential $microcredential): View
    {
        abort_unless($microcredential->user_id === $request->user()->id, 403);

        $microcredential->load(['activity', 'user']);

        return view('participant.badges.show', compact('microcredential'));
    }
}
