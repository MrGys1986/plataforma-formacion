<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('evaluator.dashboard');
    }
}
