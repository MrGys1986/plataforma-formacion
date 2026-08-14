@extends('layouts.portal')

@section('portal-name', match (true) {
    auth()->user()?->hasRole('Profesor') => 'Portal del profesor',
    auth()->user()?->hasRole('Alumno') => 'Portal del alumno',
    default => 'Portal del participante externo',
})

@section('navigation')
    @php
        $isProfessor = auth()->user()?->hasRole('Profesor') ?? false;
        $isStudent = auth()->user()?->hasRole('Alumno') ?? false;
        $isExternal = auth()->user()?->hasRole('Externo') ?? false;
        $isExternalUser = auth()->user()?->user_type === 'externo';
        $hasAcademicNavigation = $isProfessor || $isStudent || $isExternal;
        $navigationClass = static function (bool $active) use ($hasAcademicNavigation): string {
            if (! $hasAcademicNavigation) {
                return 'block rounded px-3 py-2 hover:bg-slate-100';
            }

            return $active
                ? 'block rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 font-semibold text-blue-700 shadow-sm ring-1 ring-blue-100'
                : 'block rounded-lg border border-transparent px-4 py-3 font-medium text-slate-600 transition hover:border-slate-200 hover:bg-slate-50 hover:text-blue-700';
        };
    @endphp

    @if($hasAcademicNavigation)
        <div class="mb-4 border-b border-slate-200 px-3 pb-4">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Menú principal</p>
            <p class="mt-1 text-xs text-slate-400">{{ match (true) {
                $isProfessor => 'Espacio del profesor',
                $isStudent => 'Espacio del alumno',
                default => 'Espacio del participante externo',
            } }}</p>
        </div>
    @endif

    <a class="{{ $navigationClass(request()->routeIs('participant.dashboard')) }}" href="{{ route('participant.dashboard') }}">Inicio</a>
    <a class="{{ $navigationClass(request()->routeIs('participant.catalog.*')) }}" href="{{ route('participant.catalog.index') }}">Catálogo</a>
    <a class="{{ $navigationClass(request()->routeIs('participant.my-courses.*') || request()->routeIs('participant.learning.*')) }}" href="{{ route('participant.my-courses.index') }}">Mi formación</a>
    @if($isExternal || $isExternalUser)
        <a class="{{ $navigationClass(request()->routeIs('participant.payments.*')) }}" href="{{ route('participant.payments.index') }}">Mis pagos</a>
    @endif

    @if($isProfessor)
        <div class="my-3 border-t border-slate-200"></div>
        <p class="px-4 pb-1 pt-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Actividad docente</p>
        <a class="{{ $navigationClass(request()->routeIs('participant.professor.teaching.*')) }}" href="{{ route('participant.professor.teaching.index') }}">Cursos que imparto</a>
        <div class="my-3 border-t border-slate-200"></div>
    @endif

    <a class="{{ $navigationClass(request()->routeIs('participant.learning-paths.*')) }}" href="{{ route('participant.learning-paths.index') }}">Rutas de aprendizaje</a>
    @unless($isProfessor)
        <a class="{{ $navigationClass(request()->routeIs('participant.certificates.*')) }}" href="{{ route('participant.certificates.index') }}">Constancias</a>
    @endunless
    <a class="{{ $navigationClass(request()->routeIs('participant.webinars.*')) }}" href="{{ route('participant.webinars.index') }}">Webinars</a>
    <a class="{{ $navigationClass(request()->routeIs('participant.resources.*')) }}" href="{{ route('participant.resources.index') }}">Recursos</a>
@endsection
