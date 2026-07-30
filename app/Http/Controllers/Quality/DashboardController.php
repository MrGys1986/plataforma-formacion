<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\Evidence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('quality.dashboard', [
            'stats' => [
                ['label' => 'Evidencias', 'value' => Evidence::query()->visibleTo($request->user())->count(), 'description' => 'Expedientes institucionales'],
                ['label' => 'Pendientes', 'value' => Evidence::query()->visibleTo($request->user())->where('status', 'pendiente')->count(), 'description' => 'Sin validación'],
                ['label' => 'Evaluaciones', 'value' => Evaluation::query()->visibleTo($request->user())->count(), 'description' => 'Instrumentos registrados'],
                ['label' => 'Constancias', 'value' => Certificate::query()->visibleTo($request->user())->count(), 'description' => 'Resultados documentados'],
            ],
        ]);
    }
}
