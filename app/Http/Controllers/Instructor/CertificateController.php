<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class CertificateController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('view', $activity);
        $certificates = $activity->certificates()->with('user')->latest()->paginate(20);

        return view('instructor.certificates.index', compact('activity', 'certificates'));
    }
}
