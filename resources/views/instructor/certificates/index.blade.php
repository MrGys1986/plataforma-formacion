@extends('layouts.instructor')

@section('content')
    <x-portal-page :title="'Constancias de '.$activity->name">
        <p class="text-slate-600">Constancias registradas: {{ $certificates->total() }}</p>
    </x-portal-page>
@endsection
