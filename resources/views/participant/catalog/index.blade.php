@extends('layouts.participant')

@section('content')
    <x-portal-page title="Catálogo de formación" description="Ediciones disponibles para inscripción.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($activities as $activity)
                @php
                    $courseName = Illuminate\Support\Str::lower($activity->name);
                    $courseCover = match (true) {
                        str_contains($courseName, 'liderazgo') => 'course-leadership.png',
                        str_contains($courseName, 'protección'),
                        str_contains($courseName, 'datos personales') => 'course-data-protection.png',
                        str_contains($courseName, 'diseño de experiencias') => 'course-learning-design.png',
                        str_contains($courseName, 'evaluación') => 'course-assessment.png',
                        str_contains($courseName, 'herramientas digitales') => 'course-digital-tools.png',
                        str_contains($courseName, 'inducción') => 'course-induction.png',
                        default => 'course-general.png',
                    };
                @endphp
                <a class="group flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md" href="{{ route('participant.catalog.show', $activity) }}">
                    <div class="aspect-[16/7] overflow-hidden bg-slate-100">
                        <img
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            src="{{ asset('img/courses/'.$courseCover) }}"
                            alt="Imagen de referencia del curso {{ $activity->name }}"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-600">{{ $activity->activityType?->name ?? 'Formación' }}</p>
                        <h2 class="mt-2 font-semibold text-slate-900">{{ $activity->name }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $activity->description ?: 'Actividad formativa institucional.' }}</p>
                        <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4 text-sm">
                            <span class="text-slate-500">{{ $activity->duration_hours }} horas</span>
                            <span class="font-semibold text-blue-700">Ver curso</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-slate-500">No hay actividades disponibles para inscripción.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
