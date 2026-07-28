@extends('layouts.participant')

@section('content')
    <x-portal-page title="Rutas de aprendizaje" description="Programas articulados para desarrollar competencias.">
        @forelse ($learningPaths as $learningPath)
            <a class="mb-3 block rounded-lg border p-4" href="{{ route('participant.learning-paths.show', $learningPath) }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $learningPath->name }}</p>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $learningPath->items_count }} actividades
                            @if ($learningPath->total_hours)
                                · {{ number_format((float) $learningPath->total_hours, 0) }} horas
                            @endif
                        </p>
                    </div>
                    @php($assignment = $learningPath->userLearningPaths->first())
                    @if ($assignment)
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                            {{ number_format((float) $assignment->progress_percentage, 0) }}%
                        </span>
                    @endif
                </div>
                @if ($assignment)
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-600" style="width: {{ min(100, (float) $assignment->progress_percentage) }}%"></div>
                    </div>
                @endif
            </a>
        @empty
            <p class="text-slate-500">No hay rutas publicadas.</p>
        @endforelse
        {{ $learningPaths->links() }}
    </x-portal-page>
@endsection
