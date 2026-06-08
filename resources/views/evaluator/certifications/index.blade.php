@extends('layouts.evaluator')

@section('content')
    <x-portal-page title="Certificaciones">
        <p class="text-slate-600">Certificaciones registradas: {{ $certificates->total() }}</p>
    </x-portal-page>
@endsection
