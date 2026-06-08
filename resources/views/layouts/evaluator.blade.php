@extends('layouts.portal')

@section('portal-name', 'Portal del evaluador')

@section('navigation')
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('evaluator.dashboard') }}">Inicio</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('evaluator.evidences.index') }}">Evidencias</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('evaluator.rubrics.index') }}">Rúbricas</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('evaluator.evaluations.index') }}">Evaluaciones</a>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="{{ route('evaluator.certifications.index') }}">Certificaciones</a>
@endsection
