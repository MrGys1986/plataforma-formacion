<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('participant.dashboard');
    }
}
