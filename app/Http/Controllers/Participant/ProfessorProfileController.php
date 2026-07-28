<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessorProfileController extends Controller
{
    public function __invoke(Request $request): View
    {
        $evidences = $request->user()
            ->evidences()
            ->with(['activity', 'fileUpload'])
            ->latest()
            ->paginate(10, ['*'], 'evidencias');

        $certificates = $request->user()
            ->certificates()
            ->with(['activity', 'fileUpload'])
            ->latest('issued_at')
            ->paginate(10, ['*'], 'constancias');

        return view('participant.professor-profile.index', compact('evidences', 'certificates'));
    }
}
