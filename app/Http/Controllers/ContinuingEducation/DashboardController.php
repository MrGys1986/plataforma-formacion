<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('continuing-education.dashboard', [
            'stats' => [
                ['label' => 'Oferta externa', 'value' => Activity::query()->visibleTo($request->user())->count(), 'description' => 'Actividades disponibles'],
                ['label' => 'Participantes', 'value' => User::query()->visibleTo($request->user())->count(), 'description' => 'Cuentas externas'],
                ['label' => 'Inscripciones', 'value' => Enrollment::query()->visibleTo($request->user())->count(), 'description' => 'Registros en oferta externa'],
                ['label' => 'Constancias', 'value' => Certificate::query()->visibleTo($request->user())->count(), 'description' => 'Documentos emitidos'],
            ],
            'pendingPayments' => Payment::query()->visibleTo($request->user())->where('status', 'pendiente')->count(),
            'pendingEnrollments' => Enrollment::query()->visibleTo($request->user())->where('status', 'solicitada')->count(),
        ]);
    }
}
