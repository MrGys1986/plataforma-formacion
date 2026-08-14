<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\Enrollments\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Activity $activity, EnrollmentService $enrollments): RedirectResponse
    {
        abort_unless(in_array($activity->status, ['publicado', 'en_inscripcion'], true), 422);
        abort_if($activity->instructor_id === $request->user()->id, 422, 'No puedes inscribirte en una actividad que impartes.');

        $enrollments->requestEnrollment($request->user(), $activity);

        if ($activity->requires_payment) {
            return redirect()
                ->route('participant.catalog.show', $activity)
                ->with('status', 'Completa el pago para enviar tu solicitud de inscripción.');
        }

        return redirect()
            ->route('participant.my-courses.index')
            ->with('status', 'Tu solicitud de inscripción fue registrada.');
    }
}
