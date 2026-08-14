@extends('layouts.quality')

@section('content')
    <x-portal-page title="Panel de Calidad Académica" description="Consulta evidencias, evaluaciones e indicadores para acreditación.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Evidencias', 'description' => 'Consulta los expedientes institucionales.', 'url' => route('quality.evidences.index')],
                ['label' => 'Evaluaciones', 'description' => 'Revisa instrumentos y resultados académicos.', 'url' => route('quality.evaluations.index')],
                ['label' => 'Indicadores CACEI', 'description' => 'Accede al seguimiento de acreditación.', 'url' => route('quality.cacei.index')],
                ['label' => 'CACEI', 'description' => 'Da seguimiento a categorías e indicadores.', 'url' => route('quality.cacei.index')],
                ['label' => 'ABET', 'description' => 'Consulta Student Outcomes y nivel de logro.', 'url' => route('quality.abet.index')],
                ['label' => 'ISO', 'description' => 'Revisa procesos, auditorías y hallazgos.', 'url' => route('quality.iso.index')],
                ['label' => 'Mejora continua', 'description' => 'Consulta planes de acción y compromisos.', 'url' => route('quality.improvement.index')],
                ['label' => 'Reportes', 'description' => 'Consulta resultados consolidados de calidad.', 'url' => route('quality.reports.index')],
            ]"
            :attention="[
                ['label' => 'Evidencias sin validar', 'value' => $stats[1]['value'], 'url' => route('quality.evidences.index')],
            ]"
        />
    </x-portal-page>
@endsection
