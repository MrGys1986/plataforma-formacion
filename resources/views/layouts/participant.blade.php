@extends('layouts.portal')

@section('portal-name', 'Portal del participante')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.catalog.index') }}">Catálogo</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.my-courses.index') }}">Mis cursos</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.learning-paths.index') }}">Rutas de aprendizaje</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.certificates.index') }}">Constancias</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.webinars.index') }}">Webinars</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('participant.resources.index') }}">Recursos</a>
@endsection
