<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Services\Audit\AuditService;
use App\Services\Reports\InstitutionalReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, InstitutionalReportService $reports, AuditService $audit)
    {
        $audit->log('reportes', 'consulta_calidad');

        return view('quality.reports.index', [
            'evidences' => $reports->evidenceSummary($request->user()),
            'certificates' => $reports->certificateSummary($request->user()),
        ]);
    }
}
