@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mis cursos" description="Seguimiento de tus inscripciones y avance.">
        @forelse ($enrollments as $enrollment)
            <div class="mb-3 rounded-lg border p-4">
                <p class="font-semibold">{{ $enrollment->activity?->name }}</p>
                <p class="text-sm text-slate-600">Estado: {{ ucfirst($enrollment->status) }}</p>
            </div>
        @empty
            <p class="text-slate-500">Aún no tienes inscripciones.</p>
        @endforelse
        {{ $enrollments->links() }}
    </x-portal-page>
@endsection
