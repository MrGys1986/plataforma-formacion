@extends('layouts.portal')

@section('portal-name', 'Portal del personal')

@section('navigation')
    <x-portal-nav-link :href="route('personal.dashboard')" :active="request()->routeIs('personal.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('personal.courses.index')" :active="request()->routeIs('personal.courses.*') || request()->routeIs('personal.attendance.*') || request()->routeIs('personal.evidences.*') || request()->routeIs('personal.evaluations.*') || request()->routeIs('personal.certificates.*')">Mis actividades</x-portal-nav-link>
@endsection
