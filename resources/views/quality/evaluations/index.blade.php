@extends('layouts.quality')

@section('content')
    <x-portal-page title="Evaluaciones" description="Seguimiento de instrumentos, aplicaciones y resultados académicos.">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-quality-stat label="Instrumentos" :value="$evaluations->total()" detail="Evaluaciones registradas" />
            <x-quality-stat label="Aplicaciones" :value="$evaluations->sum('results_count')" detail="En esta página" tone="slate" />
            <x-quality-stat label="Resultados aprobados" :value="$evaluations->sum('approved_results_count')" detail="En esta página" tone="emerald" />
        </div>
        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            @forelse($evaluations as $evaluation)
                @php($rate = $evaluation->results_count ? round(($evaluation->approved_results_count / $evaluation->results_count) * 100) : 0)
                <article class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm">
                    <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-wider text-blue-600">{{ ucfirst($evaluation->type ?? 'Evaluación') }}</p><h2 class="mt-1 font-semibold text-slate-900">{{ $evaluation->name }}</h2><p class="mt-1 text-sm text-slate-500">{{ $evaluation->activity?->name ?? 'Sin actividad asociada' }}</p></div><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">Mínimo {{ $evaluation->minimum_score ?? '—' }}</span></div>
                    <dl class="mt-5 grid grid-cols-2 gap-4 border-y border-slate-100 py-4"><div><dt class="text-xs text-slate-500">Aplicaciones</dt><dd class="mt-1 text-xl font-bold">{{ $evaluation->results_count }}</dd></div><div><dt class="text-xs text-slate-500">Aprobación</dt><dd class="mt-1 text-xl font-bold">{{ $rate }}%</dd></div></dl>
                    <div class="mt-4"><x-quality-progress label="Cumplimiento del instrumento" :value="$rate" :tone="$rate >= 80 ? 'emerald' : ($rate >= 60 ? 'amber' : 'rose')" /></div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 lg:col-span-2">No hay instrumentos de evaluación registrados.</div>
            @endforelse
        </div>
        @if($evaluations->hasPages())<div class="mt-6">{{ $evaluations->links() }}</div>@endif
    </x-portal-page>
@endsection
