@extends('layouts.personal')

@section('content')
    <x-portal-page title="Panel del personal" description="Consulta y administra las actividades que tienes asignadas dentro de la universidad.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Mis actividades', 'description' => 'Consulta los cursos y actividades que tienes asignados.', 'url' => route('personal.courses.index')],
            ]"
            :attention="[
                ['label' => 'Evidencias pendientes', 'value' => $pendingEvidences, 'url' => route('personal.courses.index')],
            ]"
        />
    </x-portal-page>
@endsection
