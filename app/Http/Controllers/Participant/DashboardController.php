<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()->hasRole('Profesor')) {
            $assignedCourses = $request->user()->instructedActivities()->count();
            $activeCourses = $request->user()
                ->instructedActivities()
                ->whereIn('status', ['publicado', 'en_inscripcion', 'en_curso'])
                ->count();
            $participants = $request->user()
                ->instructedActivities()
                ->withCount('enrollments')
                ->get()
                ->sum('enrollments_count');
            $learningCourses = $request->user()->enrollments()->count();
            $ownCertificates = $request->user()
                ->certificates()
                ->where('status', 'emitida')
                ->count();

            return view('participant.dashboard', compact(
                'assignedCourses',
                'activeCourses',
                'participants',
                'learningCourses',
                'ownCertificates',
            ));
        }

        if ($request->user()->hasRole('Alumno')) {
            $enrolledCourses = $request->user()->enrollments()->count();
            $activeCourses = $request->user()
                ->enrollments()
                ->where('status', 'aprobada')
                ->where('completion_status', '!=', 'completado')
                ->count();
            $completedCourses = $request->user()
                ->enrollments()
                ->where('completion_status', 'completado')
                ->count();
            $issuedCertificates = $request->user()
                ->certificates()
                ->where('status', 'emitida')
                ->count();
            $recentEnrollments = $request->user()
                ->enrollments()
                ->with('activity')
                ->latest()
                ->limit(4)
                ->get();

            return view('participant.dashboard', compact(
                'enrolledCourses',
                'activeCourses',
                'completedCourses',
                'issuedCertificates',
                'recentEnrollments',
            ));
        }

        $enrolledCourses = $request->user()->enrollments()->count();
        $activeCourses = $request->user()
            ->enrollments()
            ->where('status', 'aprobada')
            ->where('completion_status', '!=', 'completado')
            ->count();
        $pendingPayments = Payment::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'pendiente')
            ->count();
        $issuedCertificates = $request->user()
            ->certificates()
            ->where('status', 'emitida')
            ->count();
        $recentEnrollments = $request->user()
            ->enrollments()
            ->with('activity')
            ->latest()
            ->limit(4)
            ->get();

        return view('participant.dashboard', compact(
            'enrolledCourses',
            'activeCourses',
            'pendingPayments',
            'issuedCertificates',
            'recentEnrollments',
        ));
    }
}
