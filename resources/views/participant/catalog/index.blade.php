@extends('layouts.portal')

@section('content')
    <x-portal-page title="Catálogo de formación" description="Cursos, minicursos y talleres disponibles para inscripción.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($activities as $activity)
                @php
                    $courseName = Illuminate\Support\Str::lower($activity->participant_name);
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
                <a class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-950/10" href="{{ route('participant.catalog.show', ['activity' => $activity]) }}">
                    <div class="relative aspect-[16/8] overflow-hidden bg-brand-50">
                        <img
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            src="{{ $activity->coverFile?->optimizedImageUrl(960, 420) ?? $activity->trainingProgram?->coverFile?->optimizedImageUrl(960, 420) ?? asset('img/courses/'.$courseCover) }}"
                            alt="Imagen de referencia del curso {{ $activity->participant_name }}"
                            loading="lazy"
                            decoding="async"
                        >
                        <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-[0.12em] text-brand-700 shadow-sm backdrop-blur">{{ $activity->activityType?->name ?? 'Formación' }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-5 text-center">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900">{{ $activity->participant_name }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $activity->description ?: 'Actividad formativa institucional.' }}</p>
                        <div class="mt-auto pt-5 text-sm">
                            <div class="flex items-center justify-center gap-3 border-t border-slate-100 pt-4">
                                <span class="font-semibold text-slate-500">{{ $activity->duration_hours }} horas</span>
                            @if($activity->requires_payment)
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 font-bold text-amber-700">${{ number_format((float) $activity->cost, 2) }} MXN</span>
                            @else
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">Sin costo</span>
                            @endif
                            </div>
                            <span class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 font-bold text-white shadow-sm transition group-hover:bg-brand-700">Ver información <span aria-hidden="true">→</span></span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-brand-50/60 px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-700 shadow-sm"><x-portal-nav-icon name="catalog" /></span>
                    <p class="mt-4 font-semibold text-slate-700">No hay actividades disponibles para inscripción.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
