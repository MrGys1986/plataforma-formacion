@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Pagos"><p>Pagos registrados: {{ $payments->total() }}</p></x-portal-page>
@endsection
