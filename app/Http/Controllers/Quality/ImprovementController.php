<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class ImprovementController extends Controller
{
    public function index()
    {
        // TODO: registrar planes y acciones de mejora.
        return view('quality.improvement.index');
    }
}
