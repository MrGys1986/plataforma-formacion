<?php

use App\Http\Controllers\AreaManager\ActivityController;
use App\Http\Controllers\AreaManager\DashboardController;
use App\Http\Controllers\AreaManager\EnrollmentController;
use App\Http\Controllers\AreaManager\EvidenceController;
use App\Http\Controllers\AreaManager\ParticipantController;
use App\Http\Controllers\AreaManager\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('area-manager')
    ->name('area-manager.')
    ->middleware(['auth', 'role:Responsable Area', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/evidences', [EvidenceController::class, 'index'])->name('evidences.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
