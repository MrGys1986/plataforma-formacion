<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $activityIds = Activity::query()->visibleTo($request->user())->pluck('id');

        return view('area-manager.dashboard', [
            'stats' => [
                ['label' => 'Actividades del área', 'value' => $activityIds->count(), 'description' => 'Oferta bajo tu responsabilidad'],
                ['label' => 'Participantes', 'value' => User::query()->visibleTo($request->user())->count(), 'description' => 'Personal adscrito al área'],
                ['label' => 'Inscripciones', 'value' => Enrollment::query()->whereIn('activity_id', $activityIds)->count(), 'description' => 'Participaciones registradas'],
                ['label' => 'Evidencias', 'value' => Evidence::query()->whereIn('activity_id', $activityIds)->count(), 'description' => 'Documentos del área'],
            ],
            'pendingEnrollments' => Enrollment::query()->whereIn('activity_id', $activityIds)->where('status', 'solicitada')->count(),
            'pendingEvidences' => Evidence::query()->whereIn('activity_id', $activityIds)->where('status', 'pendiente')->count(),
        ]);
    }
}
