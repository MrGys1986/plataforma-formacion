<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('continuing-education.dashboard');
    }
}
