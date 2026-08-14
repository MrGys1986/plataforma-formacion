@extends('layouts.evaluator')

@section('content')
<x-portal-page title="Certificaciones" description="Constancias emitidas a participantes evaluados por ti.">
    <div class="grid gap-5 lg:grid-cols-2">
        @forelse($certificates as $certificate)
        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 bg-slate-50 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ mb_strtoupper(mb_substr($certificate->user?->name ?? '?', 0, 1)) }}</span>
                        <div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $certificate->user?->name ?? 'Usuario no disponible' }}</p><p class="truncate text-sm text-slate-500">{{ $certificate->user?->email ?? 'Sin correo' }}</p></div>
                    </div>
                    <span @class(['rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => in_array($certificate->status, ['emitida', 'disponible', 'descargada', 'reemitida'], true),'bg-amber-100 text-amber-700' => in_array($certificate->status, ['pendiente', 'bloqueada'], true),'bg-red-100 text-red-700' => $certificate->status === 'cancelada'])>{{ ucfirst($certificate->status) }}</span>
                </div>
            </div>
            <div class="p-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">{{ $certificate->activity?->activityType?->name ?? 'Actividad' }}</p>
                <h2 class="mt-2 font-semibold text-slate-900">{{ $certificate->activity?->name ?? 'Actividad no disponible' }}</h2>
                <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                    <div><dt class="font-semibold text-slate-500">Folio</dt><dd class="mt-1 text-slate-800">{{ $certificate->folio }}</dd></div>
                    <div><dt class="font-semibold text-slate-500">Emisión</dt><dd class="mt-1 text-slate-800">{{ $certificate->issued_at?->format('d/m/Y') ?? 'Pendiente' }}</dd></div>
                    <div><dt class="font-semibold text-slate-500">Calificación</dt><dd class="mt-1 text-slate-800">{{ $certificate->enrollment?->final_score ?? 'Sin registro' }}</dd></div>
                    <div><dt class="font-semibold text-slate-500">Emitida por</dt><dd class="mt-1 text-slate-800">{{ $certificate->issuedBy?->name ?? 'Sin registro' }}</dd></div>
                </dl>
                <a class="mt-5 inline-flex rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800" href="{{ route('public.certificates.verify', $certificate->folio) }}" target="_blank" rel="noopener">Verificar certificación</a>
            </div>
        </article>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 lg:col-span-2">No hay certificaciones emitidas para tus evaluaciones.</div>@endforelse
    </div>
    @if($certificates->hasPages())<div class="mt-6">{{ $certificates->links() }}</div>@endif
</x-portal-page>
@endsection
