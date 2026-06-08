@extends('layouts.rh')

@section('content')
    <x-portal-page title="Constancias"><p>Constancias registradas: {{ $certificates->total() }}</p></x-portal-page>
@endsection
