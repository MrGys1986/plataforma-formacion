<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->visibleTo($request->user())->with('area')->paginate(20);

        return view('rh.staff.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load(['area', 'enrollments.activity', 'certificates.activity']);

        return view('rh.staff.show', compact('user'));
    }
}
