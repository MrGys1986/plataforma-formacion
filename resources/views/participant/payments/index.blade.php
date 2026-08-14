@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mis pagos" description="Consulta tus pagos, comprobantes y el estado de validación de cada movimiento.">
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">Movimientos</p>
                <p class="mt-2 text-3xl font-bold text-slate-950">{{ $payments->total() }}</p>
            </div>
            <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                <p class="text-sm font-medium text-amber-700">Pendientes en esta página</p>
                <p class="mt-2 text-3xl font-bold text-slate-950">{{ $payments->getCollection()->where('status', 'pendiente')->count() }}</p>
            </div>
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-sm font-medium text-emerald-700">Validados en esta página</p>
                <p class="mt-2 text-3xl font-bold text-slate-950">{{ $payments->getCollection()->whereIn('status', ['validado', 'pagado'])->count() }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($payments as $payment)
                @php
                    $statusClass = match($payment->status) {
                        'validado', 'pagado' => 'bg-emerald-100 text-emerald-700',
                        'rechazado' => 'bg-rose-100 text-rose-700',
                        default => 'bg-amber-100 text-amber-700',
                    };
                @endphp
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="font-bold text-slate-950">{{ $payment->activity?->name ?? 'Curso no disponible' }}</h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                            </div>
                            <dl class="mt-4 grid gap-x-8 gap-y-3 text-sm sm:grid-cols-2">
                                <div><dt class="font-semibold text-slate-500">Fecha</dt><dd class="mt-1 text-slate-800">{{ $payment->created_at?->format('d/m/Y H:i') }}</dd></div>
                                <div><dt class="font-semibold text-slate-500">Método</dt><dd class="mt-1 text-slate-800">{{ ucfirst($payment->payment_method ?? 'manual') }}</dd></div>
                                <div><dt class="font-semibold text-slate-500">Referencia</dt><dd class="mt-1 text-slate-800">{{ $payment->payment_reference ?: 'Sin referencia' }}</dd></div>
                                <div><dt class="font-semibold text-slate-500">Validación</dt><dd class="mt-1 text-slate-800">{{ $payment->validated_at?->format('d/m/Y H:i') ?? 'Pendiente de revisión' }}</dd></div>
                            </dl>
                            @if($payment->observations)
                                <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700"><span class="font-semibold">Observaciones:</span> {{ $payment->observations }}</div>
                            @endif
                        </div>
                        <div class="shrink-0 lg:text-right">
                            <p class="text-2xl font-bold text-slate-950">${{ number_format((float) $payment->amount, 2) }} <span class="text-xs font-semibold text-slate-500">{{ $payment->currency }}</span></p>
                            @if($payment->proofFile)
                                <div class="mt-4 flex flex-wrap gap-2 lg:justify-end">
                                    @if(in_array($payment->proofFile->mime_type, ['application/pdf', 'image/png', 'image/jpeg'], true))
                                        <a class="rounded-lg border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50" href="{{ $payment->proofFile->temporaryPreviewUrl() }}" target="_blank" rel="noopener">Ver comprobante</a>
                                    @endif
                                    <a class="rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800" href="{{ $payment->proofFile->temporaryDownloadUrl() }}">Descargar</a>
                                </div>
                            @else
                                <p class="mt-3 text-sm text-slate-500">Sin comprobante adjunto.</p>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                    <p class="font-semibold text-slate-800">Todavía no tienes pagos registrados.</p>
                    <p class="mt-2 text-sm text-slate-500">Cuando envíes un comprobante, podrás consultar aquí su estado.</p>
                    <a class="mt-5 inline-flex rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white" href="{{ route('participant.catalog.index') }}">Explorar catálogo</a>
                </div>
            @endforelse
        </div>

        @if($payments->hasPages())
            <div class="mt-6">{{ $payments->links() }}</div>
        @endif
    </x-portal-page>
@endsection
