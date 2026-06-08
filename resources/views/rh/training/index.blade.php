@extends('layouts.rh')

@section('content')
    <x-portal-page title="Capacitación"><p>Actividades registradas: {{ $activities->total() }}</p></x-portal-page>
@endsection
