<?php

use App\Http\Controllers\Participant\BadgeController;
use App\Http\Controllers\Participant\CatalogController;
use App\Http\Controllers\Participant\CertificateController;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\Participant\EnrollmentController;
use App\Http\Controllers\Participant\EvaluationController;
use App\Http\Controllers\Participant\EvidenceController;
use App\Http\Controllers\Participant\LearningController;
use App\Http\Controllers\Participant\LearningPathController;
use App\Http\Controllers\Participant\MyCourseController;
use App\Http\Controllers\Participant\ProfessorProfileController;
use App\Http\Controllers\Participant\ResourceController;
use App\Http\Controllers\Participant\SurveyController;
use App\Http\Controllers\Participant\TeachingCourseController;
use App\Http\Controllers\Participant\WebinarController;
use Illuminate\Support\Facades\Route;

Route::prefix('participant')
    ->name('participant.')
    ->middleware(['auth', 'role:Profesor|Alumno|Externo', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{activity}', [CatalogController::class, 'show'])->name('catalog.show');
        Route::post('/catalog/{activity}/enroll', [EnrollmentController::class, 'store'])->name('catalog.enroll');
        Route::get('/my-courses', [MyCourseController::class, 'index'])->name('my-courses.index');
        Route::get('/my-courses/{enrollment}', [LearningController::class, 'show'])->name('learning.show');
        Route::post('/my-courses/{enrollment}/evidences', [EvidenceController::class, 'store'])->name('evidences.store');
        Route::get('/learning-paths', [LearningPathController::class, 'index'])->name('learning-paths.index');
        Route::get('/learning-paths/{learningPath}', [LearningPathController::class, 'show'])->name('learning-paths.show');
        Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
        Route::get('/badges/{microcredential}', [BadgeController::class, 'show'])->name('badges.show');
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/surveys/{survey}/activity/{activity}', [SurveyController::class, 'show'])->name('surveys.show');
        Route::get('/webinars', [WebinarController::class, 'index'])->name('webinars.index');
        Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');

        Route::middleware('role:Profesor')->prefix('professor')->name('professor.')->group(function (): void {
            Route::get('/teaching', [TeachingCourseController::class, 'index'])->name('teaching.index');
            Route::get('/teaching/{activity}', [TeachingCourseController::class, 'show'])->name('teaching.show');
            Route::get('/profile', ProfessorProfileController::class)->name('profile');
        });
    });
