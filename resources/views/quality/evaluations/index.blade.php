@extends('layouts.quality')

@section('content')
    <x-portal-page title="Evaluaciones"><p>Evaluaciones registradas: {{ $evaluations->total() }}</p></x-portal-page>
@endsection
