@extends('layouts.portal')

@section('portal-name', 'Portal de Recursos Humanos')

@section('navigation')
    <x-portal-nav-link :href="route('rh.dashboard')" :active="request()->routeIs('rh.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.training.index')" :active="request()->routeIs('rh.training.*')">Capacitación</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.staff.index')" :active="request()->routeIs('rh.staff.*')">Personal</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.competencies.index')" :active="request()->routeIs('rh.competencies.*')">Cursos requeridos</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.evidences.index')" :active="request()->routeIs('rh.evidences.*')">Evidencias</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.certificates.index')" :active="request()->routeIs('rh.certificates.*')">Constancias</x-portal-nav-link>
    <x-portal-nav-link :href="route('rh.reports.index')" :active="request()->routeIs('rh.reports.*')">Reportes</x-portal-nav-link>
@endsection
