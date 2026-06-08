@extends('layouts.rh')

@section('content')
    <x-portal-page :title="$user->name" :description="$user->email">
        <p>Área: {{ $user->area?->name ?? 'Sin área asignada' }}</p>
    </x-portal-page>
@endsection
