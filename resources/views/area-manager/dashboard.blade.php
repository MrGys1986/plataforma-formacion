@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Panel del responsable de área" description="Seguimiento de actividades, participantes, inscripciones y evidencias del área.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Actividades', 'description' => 'Consulta la oferta administrada por tu área.', 'url' => route('area-manager.activities.index')],
                ['label' => 'Participantes', 'description' => 'Revisa el personal adscrito y su formación.', 'url' => route('area-manager.participants.index')],
                ['label' => 'Evidencias', 'description' => 'Da seguimiento a los documentos del área.', 'url' => route('area-manager.evidences.index')],
                ['label' => 'Reportes', 'description' => 'Consulta los indicadores consolidados.', 'url' => route('area-manager.reports.index')],
            ]"
            :attention="[
                ['label' => 'Inscripciones solicitadas', 'value' => $pendingEnrollments, 'url' => route('area-manager.enrollments.index')],
                ['label' => 'Evidencias pendientes', 'value' => $pendingEvidences, 'url' => route('area-manager.evidences.index')],
            ]"
        />
    </x-portal-page>
@endsection
