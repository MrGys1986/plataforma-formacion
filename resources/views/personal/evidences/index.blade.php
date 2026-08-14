@extends('layouts.personal')

@section('content')
    <x-portal-page :title="'Evidencias de '.$activity->name" description="Entregas realizadas por los participantes de la actividad.">
        @include('personal.courses._management-nav')
        <div class="grid gap-4 lg:grid-cols-2">
            @forelse ($evidences as $evidence)
                <article class="rounded-xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="font-semibold text-slate-900">{{ $evidence->title }}</p><p class="mt-1 text-sm text-slate-500">{{ $evidence->user?->name ?? 'Usuario no disponible' }}</p></div>
                        <span @class(['rounded-full px-3 py-1 text-xs font-semibold','bg-amber-100 text-amber-700' => $evidence->status === 'pendiente','bg-emerald-100 text-emerald-700' => $evidence->status === 'validada','bg-red-100 text-red-700' => $evidence->status === 'rechazada'])>{{ ucfirst($evidence->status) }}</span>
                    </div>
                    @if($evidence->description)<p class="mt-4 text-sm leading-6 text-slate-600">{{ $evidence->description }}</p>@endif
                    <div class="mt-4 grid gap-2 border-t border-slate-100 pt-4 text-sm text-slate-500 sm:grid-cols-2">
                        <p><span class="font-semibold text-slate-700">Tipo:</span> {{ ucfirst(str_replace('_', ' ', $evidence->evidence_type)) }}</p>
                        <p><span class="font-semibold text-slate-700">Evaluador:</span> {{ $evidence->assignedEvaluator?->name ?? 'Sin asignar' }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 lg:col-span-2">No hay evidencias registradas.</div>
            @endforelse
        </div>
        @if ($evidences->hasPages())<div class="mt-6">{{ $evidences->links() }}</div>@endif
    </x-portal-page>
@endsection
