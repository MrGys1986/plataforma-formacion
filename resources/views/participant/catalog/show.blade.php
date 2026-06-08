@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$activity->name" :description="$activity->description">
        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Tipo</dt><dd>{{ $activity->activityType?->name }}</dd></div>
            <div><dt class="font-semibold">Modalidad</dt><dd>{{ ucfirst($activity->modality) }}</dd></div>
            <div><dt class="font-semibold">Duración</dt><dd>{{ $activity->duration_hours }} horas</dd></div>
            <div><dt class="font-semibold">Área</dt><dd>{{ $activity->area?->name ?? 'Institucional' }}</dd></div>
        </dl>
    </x-portal-page>
@endsection
