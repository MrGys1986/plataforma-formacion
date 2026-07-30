<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('rh.dashboard', [
            'stats' => [
                ['label' => 'Personal', 'value' => User::query()->visibleTo($request->user())->count(), 'description' => 'Cuentas institucionales'],
                ['label' => 'Capacitaciones', 'value' => Activity::query()->visibleTo($request->user())->count(), 'description' => 'Ediciones internas'],
                ['label' => 'Inscripciones', 'value' => Enrollment::query()->visibleTo($request->user())->count(), 'description' => 'Participaciones registradas'],
                ['label' => 'Constancias', 'value' => Certificate::query()->visibleTo($request->user())->count(), 'description' => 'Documentos institucionales'],
            ],
            'pendingEnrollments' => Enrollment::query()->visibleTo($request->user())->where('status', 'solicitada')->count(),
            'pendingEvidences' => Evidence::query()->visibleTo($request->user())->where('status', 'pendiente')->count(),
        ]);
    }
}
