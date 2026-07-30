@extends('layouts.rh')

@section('content')
    <x-portal-page title="Panel de Recursos Humanos" description="Seguimiento institucional de capacitación, competencias y constancias.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Capacitación', 'description' => 'Consulta la oferta y las ediciones internas.', 'url' => route('rh.training.index')],
                ['label' => 'Personal', 'description' => 'Consulta expedientes y trayectorias formativas.', 'url' => route('rh.staff.index')],
                ['label' => 'Competencias', 'description' => 'Revisa el catálogo institucional.', 'url' => route('rh.competencies.index')],
                ['label' => 'Reportes', 'description' => 'Consulta los indicadores de formación.', 'url' => route('rh.reports.index')],
            ]"
            :attention="[
                ['label' => 'Inscripciones solicitadas', 'value' => $pendingEnrollments, 'url' => route('rh.training.index')],
                ['label' => 'Evidencias pendientes', 'value' => $pendingEvidences, 'url' => route('rh.evidences.index')],
            ]"
        />
    </x-portal-page>
@endsection
