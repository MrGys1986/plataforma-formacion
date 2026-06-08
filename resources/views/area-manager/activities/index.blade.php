@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Actividades del área"><p>Actividades registradas: {{ $activities->total() }}</p></x-portal-page>
@endsection
