<?php

use App\Http\Controllers\Participant\CatalogController;
use App\Http\Controllers\Participant\CertificateController;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\Participant\EvaluationController;
use App\Http\Controllers\Participant\LearningPathController;
use App\Http\Controllers\Participant\MyCourseController;
use App\Http\Controllers\Participant\ResourceController;
use App\Http\Controllers\Participant\SurveyController;
use App\Http\Controllers\Participant\WebinarController;
use Illuminate\Support\Facades\Route;

Route::prefix('participant')
    ->name('participant.')
    ->middleware(['auth', 'role:Profesor|Alumno|Externo', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{activity}', [CatalogController::class, 'show'])->name('catalog.show');
        Route::get('/my-courses', [MyCourseController::class, 'index'])->name('my-courses.index');
        Route::get('/learning-paths', [LearningPathController::class, 'index'])->name('learning-paths.index');
        Route::get('/learning-paths/{learningPath}', [LearningPathController::class, 'show'])->name('learning-paths.show');
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/surveys/{survey}/activity/{activity}', [SurveyController::class, 'show'])->name('surveys.show');
        Route::get('/webinars', [WebinarController::class, 'index'])->name('webinars.index');
        Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    });
