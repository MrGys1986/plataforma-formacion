<?php

use App\Http\Controllers\Evaluator\CertificationController;
use App\Http\Controllers\Evaluator\DashboardController;
use App\Http\Controllers\Evaluator\EvaluationResultController;
use App\Http\Controllers\Evaluator\EvidenceReviewController;
use App\Http\Controllers\Evaluator\RubricController;
use Illuminate\Support\Facades\Route;

Route::prefix('evaluator')
    ->name('evaluator.')
    ->middleware(['auth', 'role:Evaluador', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/evidences', [EvidenceReviewController::class, 'index'])->name('evidences.index');
        Route::get('/evidences/{evidence}', [EvidenceReviewController::class, 'show'])->name('evidences.show');
        Route::get('/rubrics', [RubricController::class, 'index'])->name('rubrics.index');
        Route::get('/evaluations', [EvaluationResultController::class, 'index'])->name('evaluations.index');
        Route::get('/certifications', [CertificationController::class, 'index'])->name('certifications.index');
    });
