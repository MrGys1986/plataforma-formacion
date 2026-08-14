<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function index(Request $request)
    {
        $evidences = Evidence::query()
            ->visibleTo($request->user())
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim($request->string('search')->toString());
                $query->where(fn ($builder) => $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('activity', fn ($activity) => $activity->where('name', 'like', "%{$search}%")));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->with(['user.area', 'activity.area', 'assignedEvaluator', 'enrollment'])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('rh.evidences.index', compact('evidences'));
    }
}
