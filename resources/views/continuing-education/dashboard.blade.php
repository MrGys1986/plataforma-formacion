@extends('layouts.continuing-education')

@section('content')
    <x-portal-page title="Panel de Educación Continua" description="Gestiona oferta externa, inscripciones, pagos y constancias.">
        <x-role-dashboard
            :stats="$stats"
            :actions="[
                ['label' => 'Oferta externa', 'description' => 'Consulta las actividades de educación continua.', 'url' => route('continuing-education.offers.index')],
                ['label' => 'Participantes', 'description' => 'Administra las cuentas de participantes externos.', 'url' => route('continuing-education.external-participants.index')],
                ['label' => 'Pagos', 'description' => 'Consulta comprobantes y estados de pago.', 'url' => route('continuing-education.payments.index')],
                ['label' => 'Reportes', 'description' => 'Revisa resultados de la oferta externa.', 'url' => route('continuing-education.reports.index')],
            ]"
            :attention="[
                ['label' => 'Inscripciones solicitadas', 'value' => $pendingEnrollments, 'url' => route('continuing-education.enrollments.index')],
                ['label' => 'Pagos pendientes', 'value' => $pendingPayments, 'url' => route('continuing-education.payments.index')],
            ]"
        />
    </x-portal-page>
@endsection
