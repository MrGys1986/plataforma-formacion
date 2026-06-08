@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Inscripciones"><p>Inscripciones externas: {{ $enrollments->total() }}</p></x-portal-page>
@endsection
