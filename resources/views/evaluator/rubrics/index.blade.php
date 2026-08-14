@extends('layouts.evaluator')
@section('content')
<x-portal-page title="Rúbrica institucional" description="Una misma base de evaluación para todas las actividades y participantes.">
    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <p class="font-semibold text-blue-900">Aplicación general</p>
        <p class="mt-1 text-sm leading-6 text-blue-800">Esta rúbrica se utiliza en todos los cursos. Así se mantienen criterios consistentes y no es necesario crear una evaluación diferente para cada actividad o participante.</p>
    </div>

    <div class="grid gap-5">
        @forelse($rubrics as $rubric)
        <article class="rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Todos los cursos y actividades</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $rubric->name }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">De prueba</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Activa</span>
                </div>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $rubric->description }}</p>
            <div class="mt-5 grid gap-3 lg:grid-cols-2">
                @foreach($rubric->criteria as $criterion)
                <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                    <div class="flex justify-between gap-4">
                        <p class="font-semibold text-slate-800">{{ $criterion->name }}</p>
                        <span class="font-bold text-blue-700">{{ (float) $criterion->weight }}%</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">{{ $criterion->description }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-5 flex justify-between gap-3 border-t border-slate-100 pt-4 text-sm text-slate-500">
                <span>Aprobación: <strong class="text-slate-700">{{ (float) $rubric->passing_score }} puntos</strong></span>
                <span>{{ $rubric->criteria_count }} criterios</span>
            </div>
        </article>
        @empty
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay una rúbrica institucional activa.</div>
        @endforelse
    </div>
    @if($rubrics->hasPages())<div class="mt-6">{{ $rubrics->links() }}</div>@endif
</x-portal-page>
@endsection
