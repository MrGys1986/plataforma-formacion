<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class CertificateVerificationController extends Controller
{
    public function show(string $folio)
    {
        $certificate = Certificate::query()
            ->with(['user', 'activity'])
            ->where('folio', $folio)
            ->firstOrFail();

        return view('public.certificates.verify', compact('certificate'));
    }
}
