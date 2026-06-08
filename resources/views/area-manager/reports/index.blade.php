@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Reportes del área"><p>Inscripciones: {{ $training['total'] }} · Evidencias: {{ $evidences['total'] }}</p></x-portal-page>
@endsection
