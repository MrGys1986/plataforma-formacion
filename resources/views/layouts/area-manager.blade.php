@extends('layouts.portal')

@section('portal-name', 'Portal del responsable de área')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.activities.index') }}">Actividades</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.participants.index') }}">Participantes</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.enrollments.index') }}">Inscripciones</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.evidences.index') }}">Evidencias</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('area-manager.reports.index') }}">Reportes</a>
@endsection
