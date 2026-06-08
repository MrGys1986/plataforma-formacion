<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class AbetReportController extends Controller
{
    public function index()
    {
        // TODO: incorporar indicadores y criterios ABET.
        return view('quality.abet.index');
    }
}
