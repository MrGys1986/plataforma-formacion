<?php

use App\Http\Controllers\Quality\AbetReportController;
use App\Http\Controllers\Quality\CaceiReportController;
use App\Http\Controllers\Quality\DashboardController;
use App\Http\Controllers\Quality\EvaluationController;
use App\Http\Controllers\Quality\EvidenceController;
use App\Http\Controllers\Quality\ImprovementController;
use App\Http\Controllers\Quality\IsoReportController;
use App\Http\Controllers\Quality\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('quality')
    ->name('quality.')
    ->middleware(['auth', 'role:Calidad Academica', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/evidences', [EvidenceController::class, 'index'])->name('evidences.index');
        Route::get('/cacei', [CaceiReportController::class, 'index'])->name('cacei.index');
        Route::get('/abet', [AbetReportController::class, 'index'])->name('abet.index');
        Route::get('/iso', [IsoReportController::class, 'index'])->name('iso.index');
        Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/improvement', [ImprovementController::class, 'index'])->name('improvement.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
