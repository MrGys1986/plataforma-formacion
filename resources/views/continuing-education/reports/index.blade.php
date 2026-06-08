@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Reportes"><p>Inscripciones: {{ $training['total'] }} · Constancias: {{ $certificates['total'] }}</p></x-portal-page>
@endsection
