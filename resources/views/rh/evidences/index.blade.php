@extends('layouts.rh')

@section('content')
<x-portal-page title="Evidencias" description="Seguimiento de entregas realizadas en actividades internas de capacitación.">
    <form class="mb-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_200px_auto]" method="GET" action="{{ route('rh.evidences.index') }}">
        <input class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar evidencia, participante o actividad">
        <select class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="status">
            <option value="">Todos los estados</option>
            <option value="pendiente" @selected(request('status') === 'pendiente')>Pendientes</option>
            <option value="validada" @selected(request('status') === 'validada')>Validadas</option>
            <option value="rechazada" @selected(request('status') === 'rechazada')>Rechazadas</option>
        </select>
        <div class="flex gap-2"><button class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800" type="submit">Filtrar</button>@if(request()->hasAny(['search','status']))<a class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600" href="{{ route('rh.evidences.index') }}">Limpiar</a>@endif</div>
    </form>

    <p class="mb-4 text-sm text-slate-500"><span class="font-semibold text-slate-800">{{ $evidences->total() }}</span> evidencias encontradas</p>
    <div class="grid gap-5 xl:grid-cols-2">
        @forelse($evidences as $evidence)
        <article class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ mb_strtoupper(mb_substr($evidence->user?->name ?? '?', 0, 1)) }}</span>
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $evidence->user?->name ?? 'Usuario no disponible' }}</p><p class="truncate text-sm text-slate-500">{{ $evidence->user?->area?->name ?? 'Sin área asignada' }}</p></div>
                </div>
                <span @class(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-amber-100 text-amber-700' => $evidence->status === 'pendiente','bg-emerald-100 text-emerald-700' => $evidence->status === 'validada','bg-red-100 text-red-700' => $evidence->status === 'rechazada'])>{{ ucfirst($evidence->status) }}</span>
            </div>
            <div class="mt-5 border-l-2 border-blue-200 pl-4"><h2 class="font-semibold text-slate-900">{{ $evidence->title }}</h2><p class="mt-1 text-sm text-slate-500">{{ $evidence->activity?->name ?? 'Actividad no disponible' }}</p>@if($evidence->description)<p class="mt-2 text-sm leading-6 text-slate-600">{{ $evidence->description }}</p>@endif</div>
            <dl class="mt-5 grid gap-4 border-t border-slate-100 pt-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Tipo</dt><dd class="mt-1 text-slate-800">{{ ucfirst(str_replace('_',' ', $evidence->evidence_type)) }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Entrega</dt><dd class="mt-1 text-slate-800">{{ $evidence->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Evaluador</dt><dd class="mt-1 text-slate-800">{{ $evidence->assignedEvaluator?->name ?? 'Sin asignar' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Inscripción</dt><dd class="mt-1 text-slate-800">{{ ucfirst($evidence->enrollment?->status ?? 'sin relación') }}</dd></div>
            </dl>
        </article>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No se encontraron evidencias con los filtros seleccionados.</div>@endforelse
    </div>
    @if($evidences->hasPages())<div class="mt-6">{{ $evidences->links() }}</div>@endif
</x-portal-page>
@endsection
