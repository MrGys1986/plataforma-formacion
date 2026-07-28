@extends('layouts.participant')

@section('content')
    <x-portal-page title="Catálogo de formación" description="Ediciones disponibles para inscripción.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($activities as $activity)
                <a class="rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md" href="{{ route('participant.catalog.show', $activity) }}">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-600">{{ $activity->activityType?->name ?? 'Formación' }}</p>
                    <h2 class="mt-2 font-semibold text-slate-900">{{ $activity->name }}</h2>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $activity->description ?: 'Actividad formativa institucional.' }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-sm">
                        <span class="text-slate-500">{{ $activity->duration_hours }} horas</span>
                        <span class="font-semibold text-blue-700">Ver curso</span>
                    </div>
                </a>
            @empty
                <p class="text-slate-500">No hay actividades disponibles para inscripción.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
