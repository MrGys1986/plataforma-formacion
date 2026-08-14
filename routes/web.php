<?php

use App\Http\Controllers\SecureFileController;
use App\Http\Controllers\SecureFilePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/files/{fileUpload}/download', SecureFileController::class)
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('files.download');

Route::get('/files/{fileUpload}/preview', SecureFilePreviewController::class)
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('files.preview');

require __DIR__.'/auth.php';
