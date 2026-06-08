@extends('layouts.portal')

@section('portal-name', 'Portal de Calidad Académica')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.evidences.index') }}">Evidencias</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.cacei.index') }}">CACEI</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.abet.index') }}">ABET</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.iso.index') }}">ISO</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('quality.reports.index') }}">Reportes</a>
@endsection
