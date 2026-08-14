<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class ImprovementController extends Controller
{
    public function index()
    {
        return view('quality.improvement.index');
    }
}
