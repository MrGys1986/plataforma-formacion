@extends('layouts.instructor')

@section('content')
    <x-portal-page title="Mis actividades">
        @forelse ($activities as $activity)
            <a class="mb-3 block rounded-lg border p-4" href="{{ route('instructor.courses.show', $activity) }}">{{ $activity->name }}</a>
        @empty
            <p class="text-slate-500">No tienes actividades asignadas.</p>
        @endforelse
        {{ $activities->links() }}
    </x-portal-page>
@endsection
