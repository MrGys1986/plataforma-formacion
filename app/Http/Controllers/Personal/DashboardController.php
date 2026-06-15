<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('personal.dashboard');
    }
}
