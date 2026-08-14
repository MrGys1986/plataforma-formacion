@extends('layouts.personal')

@section('content')
    <x-portal-page :title="'Evaluaciones de '.$activity->name" description="Instrumentos y resultados vinculados con esta actividad.">
        @include('personal.courses._management-nav')
        <div class="grid gap-4 lg:grid-cols-2">
            @forelse ($evaluations as $evaluation)
                <article class="rounded-xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="font-semibold text-slate-900">{{ $evaluation->name ?? $evaluation->title ?? 'Evaluación' }}</p><p class="mt-1 text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $evaluation->evaluation_type ?? 'evaluación')) }}</p></div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $evaluation->results_count }} resultados</span>
                    </div>
                    <dl class="mt-4 grid gap-3 border-t border-slate-100 pt-4 text-sm sm:grid-cols-2">
                        <div><dt class="font-semibold text-slate-500">Puntaje mínimo</dt><dd class="mt-1 text-slate-800">{{ $evaluation->minimum_score ?? 'No definido' }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Creada por</dt><dd class="mt-1 text-slate-800">{{ $evaluation->createdBy?->name ?? 'Sin registro' }}</dd></div>
                    </dl>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 lg:col-span-2">No hay evaluaciones configuradas.</div>
            @endforelse
        </div>
        @if ($evaluations->hasPages())<div class="mt-6">{{ $evaluations->links() }}</div>@endif
    </x-portal-page>
@endsection
