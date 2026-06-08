@extends('layouts.evaluator')

@section('content')
    <x-portal-page :title="$evidence->title" :description="$evidence->description">
        <p class="text-sm">Participante: {{ $evidence->user?->name }}</p>
        <p class="text-sm">Estado: {{ ucfirst($evidence->status) }}</p>
    </x-portal-page>
@endsection
