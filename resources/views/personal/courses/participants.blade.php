@extends('layouts.personal')

@section('content')
    <x-portal-page :title="'Participantes de '.$activity->name">
        @forelse ($enrollments as $enrollment)
            <div class="mb-3 rounded-lg border p-4">{{ $enrollment->user?->name }}</div>
        @empty
            <p class="text-slate-500">No hay participantes inscritos.</p>
        @endforelse
        {{ $enrollments->links() }}
    </x-portal-page>
@endsection
