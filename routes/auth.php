<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredExternalUserController;
use App\Http\Controllers\Auth\SocialAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/register', [RegisteredExternalUserController::class, 'create'])->name('register');
    Route::get('/register/google', [SocialAuthenticationController::class, 'redirectExternalRegistration'])
        ->middleware('throttle:register')
        ->name('register.google');
    Route::get('/register/complete', [RegisteredExternalUserController::class, 'complete'])
        ->name('register.complete');
    Route::post('/register/complete', [RegisteredExternalUserController::class, 'store'])
        ->middleware('throttle:register')
        ->name('register.store');

    Route::get('/auth/google/redirect', [SocialAuthenticationController::class, 'redirectGoogle'])
        ->middleware('throttle:login')
        ->name('oauth.google.redirect');
    Route::get('/auth/google/callback', [SocialAuthenticationController::class, 'callbackGoogle'])
        ->middleware('throttle:login')
        ->name('oauth.google.callback');

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
