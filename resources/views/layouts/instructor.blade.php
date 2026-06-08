@extends('layouts.portal')

@section('portal-name', 'Portal del instructor')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('instructor.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('instructor.courses.index') }}">Mis actividades</a>
@endsection
