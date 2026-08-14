@extends('layouts.personal')

@section('content')
    <x-portal-page :title="'Constancias de '.$activity->name" description="Constancias generadas para participantes de la actividad.">
        @include('personal.courses._management-nav')
        <div class="space-y-4">
            @forelse ($certificates as $certificate)
                <article class="flex flex-col gap-4 rounded-xl border border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $certificate->user?->name ?? 'Usuario no disponible' }}</p><p class="mt-1 text-sm text-slate-500">Folio: {{ $certificate->folio ?? 'Pendiente' }} · {{ $certificate->issued_at?->format('d/m/Y') ?? 'Sin fecha de emisión' }}</p></div>
                    <span @class(['w-fit rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => $certificate->status === 'emitida','bg-amber-100 text-amber-700' => $certificate->status === 'pendiente','bg-red-100 text-red-700' => $certificate->status === 'cancelada'])>{{ ucfirst($certificate->status) }}</span>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay constancias generadas para esta actividad.</div>
            @endforelse
        </div>
        @if ($certificates->hasPages())<div class="mt-6">{{ $certificates->links() }}</div>@endif
    </x-portal-page>
@endsection
