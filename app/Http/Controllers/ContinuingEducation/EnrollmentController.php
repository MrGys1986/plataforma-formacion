<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::query()
            ->visibleTo($request->user())
            ->with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('continuing-education.enrollments.index', compact('enrollments'));
    }
}
