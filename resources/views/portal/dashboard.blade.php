@extends('layouts.portal')

@section('title', $portal['title'].' · Plataforma Institucional de Formación y Microcredenciales')

@section('content')
    <x-role-dashboard
        :portal="$portal"
        :stats="$stats"
        :actions="$portal['actions']"
        :attention="$attention"
        :recent="$recent"
    />
@endsection
