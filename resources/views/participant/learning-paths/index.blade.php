@extends('layouts.participant')

@section('content')
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 bg-slate-50/70 px-6 py-7 sm:px-8 lg:px-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-blue-700">
                        <span class="h-px w-7 bg-blue-700"></span>
                        Desarrollo profesional
                    </div>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">Rutas de aprendizaje</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600 sm:text-base">
                        Programas estructurados para fortalecer tus competencias y acompañar tu crecimiento profesional.
                    </p>
                </div>

                <div class="flex divide-x divide-slate-200 rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="px-5 py-3.5">
                        <p class="text-2xl font-bold tabular-nums text-slate-950">{{ $learningPaths->total() }}</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Rutas disponibles</p>
                    </div>
                    <div class="flex items-center px-5 py-3.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19V6.5A2.5 2.5 0 0 1 7.5 4H19v13H7.5A2.5 2.5 0 0 0 5 19Zm0 0a2 2 0 0 0 2 2h12M9 8h6m-6 3h4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="px-6 py-7 sm:px-8 lg:px-10 lg:py-9">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Programas disponibles</h2>
                    <p class="mt-1 text-sm text-slate-500">Selecciona una ruta para consultar su contenido y comenzar.</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Catálogo actualizado
                </span>
            </div>

            <div class="grid gap-5 xl:grid-cols-2">
                @forelse ($learningPaths as $learningPath)
                    @php
                        $assignment = $learningPath->userLearningPaths->first();
                        $progress = $assignment ? min(100, (float) $assignment->progress_percentage) : 0;
                    @endphp

                    <a class="group relative flex min-h-64 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg hover:shadow-slate-200/70"
                       href="{{ route('participant.learning-paths.show', $learningPath) }}">
                        <span class="absolute inset-y-0 left-0 w-1 bg-slate-200 transition-colors group-hover:bg-blue-700"></span>

                        <div class="flex items-start justify-between gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-800">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 17 5-5 3 3 8-8M15 7h5v5" />
                                </svg>
                            </span>

                            @if ($progress >= 100)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                                    Completada
                                </span>
                            @elseif ($assignment)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                    En progreso
                                </span>
                            @else
                                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">Disponible</span>
                            @endif
                        </div>

                        <div class="mt-5">
                            <h3 class="text-lg font-bold leading-snug text-slate-950 transition-colors group-hover:text-blue-800">
                                {{ $learningPath->name }}
                            </h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                {{ $learningPath->description ?: 'Programa diseñado para desarrollar habilidades y competencias de aplicación profesional.' }}
                            </p>
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" /></svg>
                                <strong class="font-semibold text-slate-800">{{ $learningPath->items_count }}</strong>
                                {{ $learningPath->items_count === 1 ? 'actividad' : 'actividades' }}
                            </span>
                            @if ($learningPath->total_hours)
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <strong class="font-semibold text-slate-800">{{ number_format((float) $learningPath->total_hours, 0) }}</strong> horas
                                </span>
                            @endif
                        </div>

                        <div class="mt-auto border-t border-slate-100 pt-5">
                            @if ($assignment)
                                <div class="flex items-center gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-2 flex items-center justify-between text-xs font-semibold">
                                            <span class="text-slate-500">Progreso general</span>
                                            <span class="tabular-nums text-blue-700">{{ number_format($progress, 0) }}%</span>
                                        </div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-blue-700" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-blue-700 transition group-hover:border-blue-700 group-hover:bg-blue-700 group-hover:text-white" aria-hidden="true">→</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-blue-700">Consultar programa</span>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-blue-700 transition group-hover:border-blue-700 group-hover:bg-blue-700 group-hover:text-white" aria-hidden="true">→</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center xl:col-span-2">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 shadow-sm">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19V6.5A2.5 2.5 0 0 1 7.5 4H19v13H7.5A2.5 2.5 0 0 0 5 19Z" /></svg>
                        </span>
                        <p class="mt-4 font-semibold text-slate-800">No hay rutas publicadas</p>
                        <p class="mt-1 text-sm text-slate-500">Los nuevos programas aparecerán en este espacio.</p>
                    </div>
                @endforelse
            </div>

            @if ($learningPaths->hasPages())
                <div class="mt-8 border-t border-slate-100 pt-6">{{ $learningPaths->links() }}</div>
            @endif
        </div>
    </section>
@endsection
