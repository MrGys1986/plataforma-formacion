<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('instructor.dashboard');
    }
}
