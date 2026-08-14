<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Evidence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $activities = Activity::query()->visibleTo($request->user());
        $activityIds = (clone $activities)->pluck('id');

        return view('personal.dashboard', [
            'stats' => [
                ['label' => 'Actividades asignadas', 'value' => (clone $activities)->count(), 'description' => 'Total bajo tu responsabilidad'],
                ['label' => 'En operación', 'value' => (clone $activities)->whereIn('status', ['publicado', 'en_inscripcion', 'en_curso'])->count(), 'description' => 'Actividades vigentes'],
                ['label' => 'Participantes', 'value' => \App\Models\Enrollment::query()->whereIn('activity_id', $activityIds)->count(), 'description' => 'Inscripciones en tus cursos'],
                ['label' => 'Evidencias pendientes', 'value' => Evidence::query()->whereIn('activity_id', $activityIds)->where('status', 'pendiente')->count(), 'description' => 'Requieren seguimiento'],
            ],
            'pendingEvidences' => Evidence::query()->whereIn('activity_id', $activityIds)->where('status', 'pendiente')->count(),
        ]);
    }
}
