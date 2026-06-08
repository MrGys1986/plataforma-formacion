@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Participantes externos"><p>Participantes registrados: {{ $users->total() }}</p></x-portal-page>
@endsection
