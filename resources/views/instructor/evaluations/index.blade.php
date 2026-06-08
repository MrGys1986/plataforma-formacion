@extends('layouts.instructor')

@section('content')
    <x-portal-page :title="'Evaluaciones de '.$activity->name">
        <p class="text-slate-600">Evaluaciones configuradas: {{ $evaluations->total() }}</p>
    </x-portal-page>
@endsection
