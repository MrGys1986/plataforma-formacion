<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Microcredential;
use Illuminate\View\View;

class BadgeVerificationController extends Controller
{
    public function show(Microcredential $microcredential): View
    {
        abort_unless($microcredential->status === 'validada', 404);

        $microcredential->load(['activity', 'user']);

        return view('public.badges.verify', compact('microcredential'));
    }
}
