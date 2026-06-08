<?php

use App\Http\Controllers\SecureFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/files/{fileUpload}/download', SecureFileController::class)
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('files.download');

require __DIR__.'/auth.php';
