@extends('layouts.participant')

@section('content')
    <x-portal-page title="Evaluaciones" description="Resultados registrados en tus actividades.">
        @forelse ($results as $result)
            <div class="mb-3 rounded-lg border p-4">
                <p class="font-semibold">{{ $result->evaluation?->name }}</p>
                <p class="text-sm text-slate-600">Resultado: {{ ucfirst($result->result) }}</p>
            </div>
        @empty
            <p class="text-slate-500">No hay resultados disponibles.</p>
        @endforelse
        {{ $results->links() }}
    </x-portal-page>
@endsection
