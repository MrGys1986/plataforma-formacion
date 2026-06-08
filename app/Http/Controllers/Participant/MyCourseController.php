<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = $request->user()
            ->enrollments()
            ->with('activity')
            ->latest()
            ->paginate(12);

        return view('participant.my-courses.index', compact('enrollments'));
    }
}
