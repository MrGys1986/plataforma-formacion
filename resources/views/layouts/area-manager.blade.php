@extends('layouts.portal')

@section('portal-name', 'Portal del responsable de área')

@section('navigation')
    <x-portal-nav-link :href="route('area-manager.dashboard')" :active="request()->routeIs('area-manager.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('area-manager.activities.index')" :active="request()->routeIs('area-manager.activities.*')">Actividades</x-portal-nav-link>
    <x-portal-nav-link :href="route('area-manager.participants.index')" :active="request()->routeIs('area-manager.participants.*')">Participantes</x-portal-nav-link>
    <x-portal-nav-link :href="route('area-manager.enrollments.index')" :active="request()->routeIs('area-manager.enrollments.*')">Inscripciones</x-portal-nav-link>
    <x-portal-nav-link :href="route('area-manager.evidences.index')" :active="request()->routeIs('area-manager.evidences.*')">Evidencias</x-portal-nav-link>
    <x-portal-nav-link :href="route('area-manager.reports.index')" :active="request()->routeIs('area-manager.reports.*')">Reportes</x-portal-nav-link>
@endsection
