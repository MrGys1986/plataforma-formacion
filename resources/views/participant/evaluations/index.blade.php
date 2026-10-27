@extends('layouts.portal')

@section('content')
    <x-portal-page title="Evaluaciones" description="Resultados registrados en tus actividades.">
        @foreach ($gradedEnrollments as $enrollment)
            <div class="mb-3 rounded-lg border p-4">
                <p class="font-semibold">{{ $enrollment->activity?->participant_name }}</p>
                <p class="text-sm text-slate-600">Calificación final: {{ $enrollment->final_score }} · {{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}</p>
            </div>
        @endforeach
        {{ $gradedEnrollments->links() }}
        @forelse ($results as $result)
            <div class="mb-3 rounded-lg border p-4">
                <p class="font-semibold">{{ $result->evaluation?->name }}</p>
                <p class="text-sm text-slate-600">Resultado: {{ ucfirst($result->result) }}</p>
            </div>
        @empty
            @if ($gradedEnrollments->isEmpty())
                <p class="text-slate-500">No hay resultados disponibles.</p>
            @endif
        @endforelse
        {{ $results->links() }}
    </x-portal-page>
@endsection
