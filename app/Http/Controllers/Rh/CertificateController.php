<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::query()
            ->visibleTo($request->user())
            ->with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('rh.certificates.index', compact('certificates'));
    }
}
