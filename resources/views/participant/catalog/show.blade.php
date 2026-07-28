@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$activity->name" :description="$activity->description">
        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Tipo</dt><dd>{{ $activity->activityType?->name }}</dd></div>
            <div><dt class="font-semibold">Modalidad</dt><dd>{{ ucfirst($activity->modality) }}</dd></div>
            <div><dt class="font-semibold">Duración</dt><dd>{{ $activity->duration_hours }} horas</dd></div>
            <div><dt class="font-semibold">Área</dt><dd>{{ $activity->area?->name ?? 'Institucional' }}</dd></div>
        </dl>

        <div class="mt-6 border-t border-slate-200 pt-5">
            @if($activity->instructor_id === auth()->id())
                <p class="rounded-lg bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">Impartes esta edición. Puedes gestionarla desde “Cursos que imparto”.</p>
            @elseif($enrollment)
                <p class="rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">Tu inscripción está {{ $enrollment->status }}.</p>
            @elseif(in_array($activity->status, ['publicado', 'en_inscripcion'], true))
                <form method="POST" action="{{ route('participant.catalog.enroll', $activity) }}">
                    @csrf
                    <button class="rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600" type="submit">
                        Solicitar inscripción
                    </button>
                </form>
            @else
                <p class="text-sm text-slate-500">Las inscripciones no están disponibles.</p>
            @endif
        </div>
    </x-portal-page>
@endsection
