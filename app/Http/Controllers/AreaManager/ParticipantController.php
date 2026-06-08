<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->visibleTo($request->user())
            ->paginate(20);

        return view('area-manager.participants.index', compact('users'));
    }
}
