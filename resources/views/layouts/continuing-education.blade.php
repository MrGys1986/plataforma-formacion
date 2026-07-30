@extends('layouts.portal')

@section('portal-name', 'Portal de Educación Continua')

@section('navigation')
    <x-portal-nav-link :href="route('continuing-education.dashboard')" :active="request()->routeIs('continuing-education.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.offers.index')" :active="request()->routeIs('continuing-education.offers.*')">Oferta</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.external-participants.index')" :active="request()->routeIs('continuing-education.external-participants.*')">Participantes externos</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.enrollments.index')" :active="request()->routeIs('continuing-education.enrollments.*')">Inscripciones</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.payments.index')" :active="request()->routeIs('continuing-education.payments.*')">Pagos</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.certificates.index')" :active="request()->routeIs('continuing-education.certificates.*')">Constancias</x-portal-nav-link>
    <x-portal-nav-link :href="route('continuing-education.reports.index')" :active="request()->routeIs('continuing-education.reports.*')">Reportes</x-portal-nav-link>
@endsection
