<?php

use App\Http\Controllers\Instructor\AttendanceController;
use App\Http\Controllers\Instructor\CertificateController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\DashboardController;
use App\Http\Controllers\Instructor\EvaluationController;
use App\Http\Controllers\Instructor\EvidenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('instructor')
    ->name('instructor.')
    ->middleware(['auth', 'role:Instructor', 'throttle:authenticated'])
    ->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{activity}', [CourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{activity}/participants', [CourseController::class, 'participants'])->name('courses.participants');
        Route::get('/courses/{activity}/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/courses/{activity}/evidences', [EvidenceController::class, 'index'])->name('evidences.index');
        Route::get('/courses/{activity}/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/courses/{activity}/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    });
