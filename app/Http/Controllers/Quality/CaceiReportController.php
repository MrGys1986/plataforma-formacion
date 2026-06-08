<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class CaceiReportController extends Controller
{
    public function index()
    {
        // TODO: incorporar indicadores y criterios CACEI.
        return view('quality.cacei.index');
    }
}
