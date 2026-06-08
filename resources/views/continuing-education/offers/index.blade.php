@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Oferta externa"><p>Actividades registradas: {{ $activities->total() }}</p></x-portal-page>
@endsection
