<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class IsoReportController extends Controller
{
    public function index()
    {
        return view('quality.iso.index');
    }
}
