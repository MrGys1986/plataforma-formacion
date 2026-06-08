<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\DigitalResource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $resources = DigitalResource::query()
            ->visibleTo($request->user())
            ->with(['area', 'activity'])
            ->latest()
            ->paginate(12);

        return view('participant.resources.index', compact('resources'));
    }
}
