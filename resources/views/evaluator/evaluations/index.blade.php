@extends('layouts.evaluator')

@section('content')
<x-portal-page title="Resultados de evaluación" description="Dictámenes y calificaciones que has registrado como evaluador.">
    <div class="space-y-4">
        @forelse($results as $result)
        <article class="rounded-xl border border-slate-200 p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ mb_strtoupper(mb_substr($result->user?->name ?? '?', 0, 1)) }}</span>
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $result->user?->name ?? 'Usuario no disponible' }}</p><p class="truncate text-sm text-slate-500">{{ $result->evaluation?->name ?? 'Evaluación no disponible' }}</p></div>
                </div>
                <div class="flex items-center gap-2">
                    <span @class(['rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => $result->result === 'aprobado','bg-red-100 text-red-700' => $result->result === 'no_aprobado','bg-amber-100 text-amber-700' => $result->result === 'pendiente'])>{{ ucfirst(str_replace('_', ' ', $result->result)) }}</span>
                    <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-bold text-white">{{ $result->score ?? '—' }} / 100</span>
                </div>
            </div>
            <div class="mt-4 border-l-2 border-blue-200 pl-4"><p class="text-sm font-semibold text-slate-700">Actividad</p><p class="mt-1 text-sm text-slate-600">{{ $result->evaluation?->activity?->name ?? 'Sin actividad' }}</p></div>
            @if($result->observations)<p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-600"><span class="font-semibold text-slate-800">Observaciones:</span> {{ $result->observations }}</p>@endif
            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 border-t border-slate-100 pt-4 text-sm text-slate-500"><span>Evaluado: {{ $result->evaluated_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}</span><span>Puntaje mínimo: {{ $result->evaluation?->minimum_score ?? 'No definido' }}</span></div>
        </article>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay resultados de evaluación asignados.</div>@endforelse
    </div>
    @if($results->hasPages())<div class="mt-6">{{ $results->links() }}</div>@endif
</x-portal-page>
@endsection
