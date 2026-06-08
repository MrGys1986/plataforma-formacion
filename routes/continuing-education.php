<?php

use App\Http\Controllers\ContinuingEducation\CertificateController;
use App\Http\Controllers\ContinuingEducation\DashboardController;
use App\Http\Controllers\ContinuingEducation\EnrollmentController;
use App\Http\Controllers\ContinuingEducation\ExternalParticipantController;
use App\Http\Controllers\ContinuingEducation\OfferController;
use App\Http\Controllers\ContinuingEducation\PaymentController;
use App\Http\Controllers\ContinuingEducation\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('continuing-education')
    ->name('continuing-education.')
    ->middleware(['auth', 'role:Educacion Continua', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
        Route::get('/external-participants', [ExternalParticipantController::class, 'index'])->name('external-participants.index');
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
