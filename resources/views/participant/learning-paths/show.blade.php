@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$learningPath->name" :description="$learningPath->description">
        <div class="mb-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-slate-500">Duración</p>
                <p class="mt-1 font-semibold">{{ number_format((float) $learningPath->total_hours, 0) }} horas</p>
            </div>
            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-slate-500">Modalidad de avance</p>
                <p class="mt-1 font-semibold">{{ $learningPath->is_sequential ? 'Secuencial' : 'Libre' }}</p>
            </div>
            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-slate-500">Tu avance</p>
                <p class="mt-1 font-semibold">
                    {{ $assignment ? number_format((float) $assignment->progress_percentage, 0).'%' : 'Ruta disponible' }}
                </p>
            </div>
        </div>

        @if ($learningPath->objective)
            <div class="mb-6 rounded-lg border bg-slate-50 p-4">
                <p class="font-semibold">Objetivo</p>
                <p class="mt-1 text-sm text-slate-700">{{ $learningPath->objective }}</p>
            </div>
        @endif

        <h2 class="mb-3 text-lg font-semibold">Actividades</h2>
        @forelse ($items as $state)
            @php($item = $state->item)
            <div class="mb-3 rounded-lg border p-4 {{ $state->unlocked ? 'bg-white' : 'bg-slate-50 opacity-70' }}">
                <div class="flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold {{ $state->completed ? 'bg-emerald-100 text-emerald-700' : ($state->unlocked ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-500') }}">
                        {{ $state->completed ? '✓' : $item->order_number }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-semibold">{{ $item->activity?->name }}</p>
                            <span class="text-sm font-medium {{ $state->completed ? 'text-emerald-700' : 'text-slate-500' }}">
                                @if ($state->completed)
                                    Completada
                                @elseif (! $state->unlocked)
                                    Bloqueada
                                @elseif ($state->enrollment)
                                    Disponible
                                @else
                                    Sin asignar
                                @endif
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ number_format((float) ($item->activity?->duration_hours ?? 0), 0) }} horas
                            · {{ $item->is_required ? 'Obligatoria' : 'Opcional' }}
                            @if ($item->minimum_score !== null)
                                · Calificación mínima: {{ number_format((float) $item->minimum_score, 0) }}
                            @endif
                        </p>
                        @if ($state->unlocked && $state->enrollment)
                            <a class="mt-3 inline-flex rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                               href="{{ route('participant.learning.show', $state->enrollment) }}">
                                {{ $state->completed ? 'Revisar actividad' : 'Continuar' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-slate-500">Esta ruta aún no tiene actividades.</p>
        @endforelse
    </x-portal-page>
@endsection
