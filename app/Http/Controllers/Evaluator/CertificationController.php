<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::query()
            ->visibleTo($request->user())
            ->with(['user', 'activity.activityType', 'enrollment', 'issuedBy'])
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('evaluator.certifications.index', compact('certificates'));
    }
}
