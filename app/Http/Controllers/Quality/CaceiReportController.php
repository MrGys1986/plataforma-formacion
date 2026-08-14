<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class CaceiReportController extends Controller
{
    public function index()
    {
        return view('quality.cacei.index');
    }
}
