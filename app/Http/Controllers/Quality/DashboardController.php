<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('quality.dashboard');
    }
}
