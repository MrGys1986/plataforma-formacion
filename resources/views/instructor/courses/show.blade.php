@extends('layouts.instructor')

@section('content')
    <x-portal-page :title="$activity->name" :description="$activity->description">
        <div class="flex flex-wrap gap-3 text-sm">
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('instructor.courses.participants', $activity) }}">Participantes</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('instructor.attendance.index', $activity) }}">Asistencia</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('instructor.evidences.index', $activity) }}">Evidencias</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('instructor.evaluations.index', $activity) }}">Evaluaciones</a>
        </div>
    </x-portal-page>
@endsection
