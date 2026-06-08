<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
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
        ]);
    }
}
