<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ExternalParticipantController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->visibleTo($request->user())
            ->paginate(20);

        return view('continuing-education.external-participants.index', compact('users'));
    }
}
