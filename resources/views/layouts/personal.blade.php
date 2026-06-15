@extends('layouts.portal')

@section('portal-name', 'Portal del personal')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('personal.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('personal.courses.index') }}">Mis actividades</a>
@endsection
