@extends('layouts.portal')

@section('portal-name', 'Portal del evaluador')

@section('navigation')
    <x-portal-nav-link :href="route('evaluator.dashboard')" :active="request()->routeIs('evaluator.dashboard')">Inicio</x-portal-nav-link>
    <x-portal-nav-link :href="route('evaluator.evidences.index')" :active="request()->routeIs('evaluator.evidences.*')">Evidencias</x-portal-nav-link>
    <x-portal-nav-link :href="route('evaluator.rubrics.index')" :active="request()->routeIs('evaluator.rubrics.*')">Rúbricas</x-portal-nav-link>
    <x-portal-nav-link :href="route('evaluator.evaluations.index')" :active="request()->routeIs('evaluator.evaluations.*')">Evaluaciones</x-portal-nav-link>
    <x-portal-nav-link :href="route('evaluator.certifications.index')" :active="request()->routeIs('evaluator.certifications.*')">Certificaciones</x-portal-nav-link>
@endsection
