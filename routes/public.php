<?php

use App\Http\Controllers\Public\BadgeVerificationController;
use App\Http\Controllers\Public\CertificateVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/certificates/verify/{folio}', [CertificateVerificationController::class, 'show'])
    ->middleware('throttle:public-certificates')
    ->name('public.certificates.verify');

Route::get('/badges/verify/{microcredential}', [BadgeVerificationController::class, 'show'])
    ->middleware('throttle:public-certificates')
    ->name('public.badges.verify');
