@extends('layouts.participant')

@section('content')
    @php
        $progress = min(100, (float) $progressPercentage);
        $completedCount = $items->where('completed', true)->count();
    @endphp

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 bg-slate-50/70 px-6 py-7 sm:px-8 lg:px-10">
            <a class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-blue-700"
               href="{{ route('participant.learning-paths.index') }}">
                <span aria-hidden="true">←</span>
                Todas las rutas
            </a>

            <div class="mt-6 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-3xl">
                    <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-blue-700">
                        <span class="h-px w-7 bg-blue-700"></span>
                        Ruta de aprendizaje
                    </span>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $learningPath->name }}</h1>
                    @if ($learningPath->description)
                        <p class="mt-3 text-base leading-7 text-slate-600">{{ $learningPath->description }}</p>
                    @endif
                </div>

                <div class="w-full rounded-xl border border-slate-200 bg-white p-4 shadow-sm lg:w-72">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-500">Progreso general</span>
                        <strong class="tabular-nums text-blue-700">{{ number_format($progress, 0) }}%</strong>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-700 transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">{{ $completedCount }} de {{ $items->count() }} actividades completadas</p>
                </div>
            </div>
        </header>

        <div class="px-6 py-7 sm:px-8 lg:px-10 lg:py-9">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </span>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Duración</p><p class="mt-1 font-bold text-slate-900">{{ number_format((float) $learningPath->total_hours, 0) }} horas</p></div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h7m-7 6h16M13 18h7m-6-9 3 3-3 3M10 15l-3 3-3-3" /></svg>
                    </span>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Modalidad</p><p class="mt-1 font-bold text-slate-900">{{ $learningPath->is_sequential ? 'Avance secuencial' : 'Avance libre' }}</p></div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 p-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m4 17 5-5 3 3 8-8M15 7h5v5" /></svg>
                    </span>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Estado</p><p class="mt-1 font-bold text-slate-900">{{ $progress >= 100 ? 'Completada' : ($progress > 0 ? 'En progreso' : 'Disponible') }}</p></div>
                </div>
            </div>

            @if ($learningPath->objective)
                <div class="mt-6 flex gap-4 rounded-xl border border-blue-100 bg-blue-50/60 p-5 sm:p-6">
                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-blue-700 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-13v4l2.5 2.5M12 7.75V8" /></svg>
                    </span>
                    <div><h2 class="font-bold text-slate-900">Objetivo de la ruta</h2><p class="mt-1 text-sm leading-6 text-slate-600">{{ $learningPath->objective }}</p></div>
                </div>
            @endif

            <div class="mt-9">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-950">Plan de actividades</h2>
                        <p class="mt-1 text-sm text-slate-500">Consulta la ficha de cada actividad en el catálogo para conocer todos sus detalles.</p>
                    </div>
                    <span class="text-sm font-medium text-slate-500">{{ $items->count() }} {{ $items->count() === 1 ? 'actividad' : 'actividades' }}</span>
                </div>

                <div class="relative mt-6 space-y-4">
                    <span class="absolute bottom-8 left-6 top-8 hidden w-px bg-slate-200 sm:block" aria-hidden="true"></span>

                    @forelse ($items as $state)
                        @php
                            $item = $state->item;
                            $activity = $item->activity;
                            $typeName = $activity?->activityType?->name ?? 'Actividad';
                        @endphp

                        <article class="group relative rounded-xl border bg-white p-5 transition duration-200 hover:border-blue-300 hover:shadow-md sm:pl-20 {{ $state->completed ? 'border-emerald-200' : 'border-slate-200' }}">
                            <span class="relative z-10 mb-4 flex h-12 w-12 items-center justify-center rounded-xl border-4 border-white text-sm font-bold shadow-sm sm:absolute sm:left-0 sm:top-1/2 sm:mb-0 sm:-translate-y-1/2 {{ $state->completed ? 'bg-emerald-600 text-white' : ($state->unlocked ? 'bg-blue-700 text-white' : 'bg-slate-200 text-slate-500') }}">
                                @if ($state->completed)
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                                @else
                                    {{ $item->order_number }}
                                @endif
                            </span>

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-bold uppercase tracking-wide text-slate-600">{{ $typeName }}</span>
                                        @if ($item->is_required)
                                            <span class="text-xs font-semibold text-slate-400">Obligatoria</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">Opcional</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-2 text-lg font-bold text-slate-950">{{ $activity?->name }}</h3>
                                    <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            {{ number_format((float) ($activity?->duration_hours ?? 0), 0) }} horas
                                        </span>
                                        @if ($item->minimum_score !== null)
                                            <span>Calificación mínima: <strong class="font-semibold text-slate-700">{{ number_format((float) $item->minimum_score, 0) }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                                    <span class="mr-1 text-xs font-semibold {{ $state->completed ? 'text-emerald-700' : ($state->unlocked ? 'text-blue-700' : 'text-slate-400') }}">
                                        {{ $state->completed ? 'Completada' : ($state->unlocked ? ($state->enrollment ? 'Disponible' : 'Sin asignar') : 'Bloqueada en la ruta') }}
                                    </span>

                                    @if ($activity)
                                        <a class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-700 hover:text-blue-700"
                                           href="{{ route('participant.catalog.show', $activity) }}">
                                            Ver en catálogo
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    @endif

                                    @if ($state->unlocked && $state->enrollment)
                                        <a class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800"
                                           href="{{ route('participant.learning.show', $state->enrollment) }}">
                                            {{ $state->completed ? 'Revisar actividad' : 'Continuar aprendizaje' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                            <p class="font-semibold text-slate-700">Esta ruta aún no tiene actividades.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
