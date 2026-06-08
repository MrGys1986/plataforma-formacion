@extends('layouts.portal')

@section('portal-name', 'Portal de Educación Continua')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.offers.index') }}">Oferta</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.external-participants.index') }}">Participantes externos</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.enrollments.index') }}">Inscripciones</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.payments.index') }}">Pagos</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('continuing-education.reports.index') }}">Reportes</a>
@endsection
