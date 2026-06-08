@extends('layouts.rh')

@section('content')
    <x-portal-page title="Reportes institucionales">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-lg border p-4">Inscripciones: {{ $training['total'] }}</div>
            <div class="rounded-lg border p-4">Evidencias: {{ $evidences['total'] }}</div>
            <div class="rounded-lg border p-4">Constancias: {{ $certificates['total'] }}</div>
        </div>
    </x-portal-page>
@endsection
