<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class CertificateController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('view', $activity);
        $certificates = $activity->certificates()
            ->with(['user', 'issuedBy'])
            ->latest()
            ->paginate(20);

        return view('personal.certificates.index', compact('activity', 'certificates'));
    }
}
