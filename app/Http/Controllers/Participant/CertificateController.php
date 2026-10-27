<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = $request->user()
            ->certificates()
            ->where('status', 'emitida')
            ->with(['activity.activityType', 'activity.coverFile', 'activity.trainingProgram.coverFile', 'fileUpload'])
            ->latest('issued_at')
            ->paginate(12);

        return view('participant.certificates.index', compact('certificates'));
    }
}
