@extends('layouts.instructor')

@section('content')
    <x-portal-page :title="'Evidencias de '.$activity->name">
        <p class="text-slate-600">Evidencias registradas: {{ $evidences->total() }}</p>
    </x-portal-page>
@endsection
