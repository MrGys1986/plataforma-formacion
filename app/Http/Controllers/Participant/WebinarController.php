<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use Illuminate\Http\Request;

class WebinarController extends Controller
{
    public function index(Request $request)
    {
        $webinars = Webinar::query()
            ->visibleTo($request->user())
            ->orderBy('start_datetime')
            ->paginate(12);

        return view('participant.webinars.index', compact('webinars'));
    }
}
