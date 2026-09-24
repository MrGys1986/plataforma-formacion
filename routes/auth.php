<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SocialAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/auth/microsoft/redirect', [SocialAuthenticationController::class, 'redirectMicrosoft'])
        ->middleware('throttle:login')
        ->name('oauth.microsoft.redirect');
    Route::get('/auth/microsoft/callback', [SocialAuthenticationController::class, 'callbackMicrosoft'])
        ->middleware('throttle:login')
        ->name('oauth.microsoft.callback');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
