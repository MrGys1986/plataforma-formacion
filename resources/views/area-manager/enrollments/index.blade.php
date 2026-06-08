@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Inscripciones del área"><p>Inscripciones registradas: {{ $enrollments->total() }}</p></x-portal-page>
@endsection
