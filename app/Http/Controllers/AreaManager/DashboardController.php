<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('area-manager.dashboard');
    }
}
