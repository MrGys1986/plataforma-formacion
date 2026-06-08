@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Constancias"><p>Constancias externas: {{ $certificates->total() }}</p></x-portal-page>
@endsection
