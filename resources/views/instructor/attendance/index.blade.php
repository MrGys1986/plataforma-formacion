@extends('layouts.instructor')

@section('content')
    <x-portal-page :title="'Asistencia de '.$activity->name" description="Registro básico preparado para sesiones del curso.">
        <p class="text-slate-600">Participantes registrados: {{ $attendanceRecords->total() }}</p>
    </x-portal-page>
@endsection
