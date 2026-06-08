@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Participantes del área"><p>Participantes registrados: {{ $users->total() }}</p></x-portal-page>
@endsection
