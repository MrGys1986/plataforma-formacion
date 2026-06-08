<?php

use App\Http\Controllers\Rh\CertificateController;
use App\Http\Controllers\Rh\CompetencyController;
use App\Http\Controllers\Rh\DashboardController;
use App\Http\Controllers\Rh\EvidenceController;
use App\Http\Controllers\Rh\ReportController;
use App\Http\Controllers\Rh\StaffController;
use App\Http\Controllers\Rh\TrainingController;
use Illuminate\Support\Facades\Route;

Route::prefix('rh')
    ->name('rh.')
    ->middleware(['auth', 'role:Recursos Humanos', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/{user}', [StaffController::class, 'show'])->name('staff.show');
        Route::get('/competencies', [CompetencyController::class, 'index'])->name('competencies.index');
        Route::get('/evidences', [EvidenceController::class, 'index'])->name('evidences.index');
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
