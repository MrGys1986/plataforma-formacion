@extends('layouts.participant')

@section('content')
    <x-portal-page title="Rutas de aprendizaje" description="Programas articulados para desarrollar competencias.">
        @forelse ($learningPaths as $learningPath)
            <a class="mb-3 block rounded-lg border p-4" href="{{ route('participant.learning-paths.show', $learningPath) }}">
                <p class="font-semibold">{{ $learningPath->name }}</p>
                <p class="text-sm text-slate-600">{{ $learningPath->items_count }} actividades</p>
            </a>
        @empty
            <p class="text-slate-500">No hay rutas publicadas.</p>
        @endforelse
        {{ $learningPaths->links() }}
    </x-portal-page>
@endsection
