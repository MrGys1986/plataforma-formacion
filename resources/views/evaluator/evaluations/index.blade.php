@extends('layouts.evaluator')

@section('content')
    <x-portal-page title="Resultados de evaluación">
        <p class="text-slate-600">Resultados registrados: {{ $results->total() }}</p>
    </x-portal-page>
@endsection
