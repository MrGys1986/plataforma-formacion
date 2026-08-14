@extends('layouts.rh')

@section('content')
<x-portal-page title="Capacitación" description="Oferta interna de formación y seguimiento de participación institucional.">
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-slate-900 p-5 text-white"><p class="text-sm text-slate-300">Actividades</p><p class="mt-2 text-3xl font-bold">{{ $activities->total() }}</p></div>
        <div class="rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Inscripciones en esta página</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $activities->sum('enrollments_count') }}</p></div>
        <div class="rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Evidencias en esta página</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $activities->sum('evidences_count') }}</p></div>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        @forelse($activities as $activity)
        <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">{{ $activity->activityType?->name ?? 'Actividad' }}</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $activity->name }}</h2>
                </div>
                <span @class(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => in_array($activity->status, ['publicado','en_inscripcion','en_curso','finalizado'], true),'bg-amber-100 text-amber-700' => $activity->status === 'borrador','bg-red-100 text-red-700' => $activity->status === 'cancelado','bg-slate-100 text-slate-600' => !in_array($activity->status, ['publicado','en_inscripcion','en_curso','finalizado','borrador','cancelado'], true)])>{{ ucfirst(str_replace('_', ' ', $activity->status)) }}</span>
            </div>
            @if($activity->description)<p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $activity->description }}</p>@endif

            <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Área</dt><dd class="mt-1 text-slate-800">{{ $activity->area?->name ?? 'Sin área' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Instructor</dt><dd class="mt-1 text-slate-800">{{ $activity->instructor?->name ?? 'Sin asignar' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Modalidad</dt><dd class="mt-1 text-slate-800">{{ ucfirst($activity->modality) }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Periodo</dt><dd class="mt-1 text-slate-800">{{ $activity->start_date?->format('d/m/Y') ?? 'Por definir' }} – {{ $activity->end_date?->format('d/m/Y') ?? 'Por definir' }}</dd></div>
            </dl>

            <div class="mt-auto grid grid-cols-3 gap-3 pt-5 text-center text-sm">
                <div class="rounded-lg bg-blue-50 p-3"><p class="text-xl font-bold text-blue-700">{{ $activity->enrollments_count }}</p><p class="mt-1 text-xs text-blue-600">Inscripciones</p></div>
                <div class="rounded-lg bg-amber-50 p-3"><p class="text-xl font-bold text-amber-700">{{ $activity->evidences_count }}</p><p class="mt-1 text-xs text-amber-600">Evidencias</p></div>
                <div class="rounded-lg bg-emerald-50 p-3"><p class="text-xl font-bold text-emerald-700">{{ $activity->certificates_count }}</p><p class="mt-1 text-xs text-emerald-600">Constancias</p></div>
            </div>
        </article>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No hay actividades internas registradas.</div>@endforelse
    </div>
    @if($activities->hasPages())<div class="mt-6">{{ $activities->links() }}</div>@endif
</x-portal-page>
@endsection
