@extends('layouts.quality')

@section('content')
    <x-portal-page title="Evidencias"><p>Evidencias registradas: {{ $evidences->total() }}</p></x-portal-page>
@endsection
