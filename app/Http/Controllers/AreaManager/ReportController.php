<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Services\Audit\AuditService;
use App\Services\Reports\InstitutionalReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, InstitutionalReportService $reports, AuditService $audit)
    {
        $audit->log('reportes', 'consulta_area');

        return view('area-manager.reports.index', [
            'training' => $reports->trainingSummary($request->user()),
            'evidences' => $reports->evidenceSummary($request->user()),
            'participantsCount' => User::query()->visibleTo($request->user())->count(),
            'activities' => Activity::query()
                ->visibleTo($request->user())
                ->with('activityType')
                ->withCount([
                    'enrollments',
                    'enrollments as approved_enrollments_count' => fn ($query) => $query->where('status', 'aprobada'),
                    'enrollments as requested_enrollments_count' => fn ($query) => $query->where('status', 'solicitada'),
                    'evidences',
                    'evidences as pending_evidences_count' => fn ($query) => $query->where('status', 'pendiente'),
                    'evidences as validated_evidences_count' => fn ($query) => $query->where('status', 'validada'),
                ])
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }
}
