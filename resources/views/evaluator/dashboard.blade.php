@extends('layouts.evaluator')

@section('content')
    <x-portal-page title="Panel del evaluador" description="Revisa evidencias, resultados y procesos de certificación.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Evidencias', 'description' => 'Abre tu bandeja de documentos asignados.', 'url' => route('evaluator.evidences.index')],
                ['label' => 'Evaluaciones', 'description' => 'Consulta los resultados que has registrado.', 'url' => route('evaluator.evaluations.index')],
                ['label' => 'Rúbricas', 'description' => 'Consulta los criterios institucionales.', 'url' => route('evaluator.rubrics.index')],
                ['label' => 'Certificaciones', 'description' => 'Revisa los resultados de certificación visibles.', 'url' => route('evaluator.certifications.index')],
            ]"
            :attention="[
                ['label' => 'Evidencias por revisar', 'value' => $stats[1]['value'], 'url' => route('evaluator.evidences.index')],
            ]"
        />
    </x-portal-page>
@endsection
