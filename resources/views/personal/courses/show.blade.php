@extends('layouts.personal')

@section('content')
    <x-portal-page :title="$activity->name" :description="$activity->description">
        <div class="flex flex-wrap gap-3 text-sm">
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('personal.courses.participants', $activity) }}">Participantes</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('personal.attendance.index', $activity) }}">Asistencia</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('personal.evidences.index', $activity) }}">Evidencias</a>
            <a class="rounded bg-slate-900 px-3 py-2 text-white" href="{{ route('personal.evaluations.index', $activity) }}">Evaluaciones</a>
        </div>
    </x-portal-page>
@endsection
