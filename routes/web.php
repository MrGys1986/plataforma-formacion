<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\TrainingWorkflowController;
use App\Http\Controllers\SecureFileController;
use App\Http\Controllers\SecureFilePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'throttle:authenticated'])
    ->name('dashboard');

Route::middleware(['auth', 'role:Superadministrador', 'throttle:authenticated'])
    ->prefix('dashboard/administracion')
    ->name('dashboard.admin.')
    ->group(function (): void {
        Route::post('/programas', [TrainingWorkflowController::class, 'storeProgram'])->name('programs.store');
        Route::post('/actividades', [TrainingWorkflowController::class, 'storeActivity'])->name('activities.store');
        Route::post('/actividades/{activity}/inscripciones', [TrainingWorkflowController::class, 'storeEnrollment'])->name('activities.enrollments.store');
        Route::patch('/actividades/{activity}/classroom', [TrainingWorkflowController::class, 'updateClassroom'])->name('activities.classroom');
        Route::post('/actividades/{activity}/documentos', [TrainingWorkflowController::class, 'storeDocument'])->name('activities.documents.store');
        Route::post('/actividades/{activity}/constancias', [TrainingWorkflowController::class, 'storeCertificate'])->name('activities.certificates.store');
    });

Route::get('/files/{fileUpload}/download', SecureFileController::class)
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('files.download');

Route::get('/files/{fileUpload}/preview', SecureFilePreviewController::class)
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('files.preview');

require __DIR__.'/auth.php';
