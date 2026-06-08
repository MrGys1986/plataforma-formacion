@extends('layouts.quality')

@section('content')
    <x-portal-page title="Reportes de calidad">
        <p>Evidencias: {{ $evidences['total'] }} · Constancias: {{ $certificates['total'] }}</p>
    </x-portal-page>
@endsection
