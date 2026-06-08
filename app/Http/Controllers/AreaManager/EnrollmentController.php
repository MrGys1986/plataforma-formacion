<?php

namespace App\Http\Controllers\AreaManager;

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

        return view('area-manager.enrollments.index', compact('enrollments'));
    }
}
