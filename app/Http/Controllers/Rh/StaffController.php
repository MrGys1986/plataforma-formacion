<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->visibleTo($request->user())
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim($request->string('search')->toString());
                $query->where(fn ($builder) => $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('area'), fn ($query) => $query->where('area_id', $request->integer('area')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->with(['area', 'roles'])
            ->withCount(['enrollments', 'certificates'])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $areas = Area::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('rh.staff.index', compact('users', 'areas'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        $user->load(['area', 'enrollments.activity', 'certificates.activity']);

        return view('rh.staff.show', compact('user'));
    }
}
