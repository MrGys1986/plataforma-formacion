@extends('layouts.participant')

@section('content')
    <x-portal-page title="Catálogo de formación" description="Actividades disponibles para inscripción.">
        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($activities as $activity)
                <a class="rounded-lg border p-4 hover:border-slate-400" href="{{ route('participant.catalog.show', $activity) }}">
                    <h2 class="font-semibold">{{ $activity->name }}</h2>
                    <p class="text-sm text-slate-600">{{ $activity->activityType?->name }}</p>
                </a>
            @empty
                <p class="text-slate-500">No hay actividades publicadas.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
