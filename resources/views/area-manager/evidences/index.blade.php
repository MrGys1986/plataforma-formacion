@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Evidencias del área"><p>Evidencias registradas: {{ $evidences->total() }}</p></x-portal-page>
@endsection
