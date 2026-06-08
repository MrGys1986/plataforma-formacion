<?php

use App\Http\Controllers\Public\CertificateVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/certificates/verify/{folio}', [CertificateVerificationController::class, 'show'])
    ->middleware('throttle:public-certificates')
    ->name('public.certificates.verify');
