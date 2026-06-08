@extends('layouts.portal')

@section('portal-name', 'Portal de Recursos Humanos')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('rh.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('rh.training.index') }}">Capacitación</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('rh.staff.index') }}">Personal</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('rh.competencies.index') }}">Competencias</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('rh.reports.index') }}">Reportes</a>
@endsection
