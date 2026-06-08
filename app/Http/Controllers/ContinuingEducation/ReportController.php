<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Services\Audit\AuditService;
use App\Services\Reports\InstitutionalReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request, InstitutionalReportService $reports, AuditService $audit)
    {
        $audit->log('reportes', 'consulta_educacion_continua');

        return view('continuing-education.reports.index', [
            'training' => $reports->trainingSummary($request->user()),
            'certificates' => $reports->certificateSummary($request->user()),
        ]);
    }
}
