@extends('layouts.portal')

@section('portal-name', 'Portal de Calidad Académica')

@section('navigation')
    <x-portal-nav-link :href="route('quality.dashboard')" :active="request()->routeIs('quality.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.evidences.index')" :active="request()->routeIs('quality.evidences.*')">Evidencias</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.evaluations.index')" :active="request()->routeIs('quality.evaluations.*')">Evaluaciones</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.cacei.index')" :active="request()->routeIs('quality.cacei.*')">CACEI</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.abet.index')" :active="request()->routeIs('quality.abet.*')">ABET</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.iso.index')" :active="request()->routeIs('quality.iso.*')">ISO</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.improvement.index')" :active="request()->routeIs('quality.improvement.*')">Mejora continua</x-portal-nav-link>
    <x-portal-nav-link :href="route('quality.reports.index')" :active="request()->routeIs('quality.reports.*')">Reportes</x-portal-nav-link>
@endsection
