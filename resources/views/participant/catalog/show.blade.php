@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$activity->name" :description="$activity->description">
        @php($coverFile = $activity->coverFile ?? $activity->trainingProgram?->coverFile)
        @if($coverFile)
            <img class="mb-6 aspect-[16/6] w-full rounded-xl object-cover" src="{{ $coverFile->optimizedImageUrl(1400, 525) }}" alt="Portada de {{ $activity->name }}">
        @endif
        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Tipo</dt><dd>{{ $activity->activityType?->name }}</dd></div>
            <div><dt class="font-semibold">Modalidad</dt><dd>{{ ucfirst($activity->modality) }}</dd></div>
            <div><dt class="font-semibold">Duración</dt><dd>{{ $activity->duration_hours }} horas</dd></div>
            <div><dt class="font-semibold">Área</dt><dd>{{ $activity->area?->name ?? 'Institucional' }}</dd></div>
            <div><dt class="font-semibold">Profesor</dt><dd>{{ $activity->instructor?->name ?? 'Por asignar' }}</dd></div>
            <div><dt class="font-semibold">Costo</dt><dd>{{ $activity->requires_payment ? '$'.number_format((float) $activity->cost, 2).' MXN' : 'Sin costo' }}</dd></div>
        </dl>

        <div class="mt-6 border-t border-slate-200 pt-5">
            @if($activity->instructor_id === auth()->id())
                <p class="rounded-lg bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700">Impartes esta actividad. Puedes gestionarla desde “Cursos que imparto”.</p>
            @elseif($enrollment && $activity->requires_payment && ! $enrollment->requested_at)
                <p class="rounded-lg bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">Tu lugar aún no está solicitado. Completa el pago para enviar la inscripción.</p>
            @elseif($enrollment)
                <p class="rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">Tu inscripción está {{ $enrollment->status }}.</p>
            @elseif(in_array($activity->status, ['publicado', 'en_inscripcion'], true))
                <form method="POST" action="{{ route('participant.catalog.enroll', $activity) }}">
                    @csrf
                    <button class="rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600" type="submit">
                        {{ $activity->requires_payment ? 'Inscribirme y proceder al pago' : 'Solicitar inscripción' }}
                    </button>
                </form>
            @else
                <p class="text-sm text-slate-500">Las inscripciones no están disponibles.</p>
            @endif
        </div>

        @if($enrollment && $activity->requires_payment && $latestPayment?->status === 'pendiente')
            <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-800">
                <p class="font-semibold">Tu comprobante está en revisión.</p>
                <p class="mt-1">La solicitud se enviará al profesor cuando el pago sea validado. Consulta el estado desde <a class="font-bold underline" href="{{ route('participant.payments.index') }}">Mis pagos</a>.</p>
            </div>
        @endif

        @if($enrollment && $activity->requires_payment && (! $latestPayment || $latestPayment->status === 'rechazado'))
            <section class="mt-6 overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm" x-data="{ method: '{{ old('payment_method', 'tarjeta') }}' }">
                <div class="border-b border-amber-100 bg-amber-50 px-6 py-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-amber-700">Paso requerido para inscribirte</p><h2 class="mt-1 text-xl font-bold text-slate-950">Completa los datos de pago</h2></div>
                        <p class="text-2xl font-bold text-slate-950">${{ number_format((float) $activity->cost, 2) }} <span class="text-sm font-semibold text-slate-500">MXN</span></p>
                    </div>
                    <p class="mt-3 text-sm text-amber-800">Demostración visual: la tarjeta no será cobrada ni sus datos se enviarán o guardarán.</p>
                </div>
                <form class="grid gap-6 p-6 lg:grid-cols-[1.1fr_.9fr]" method="POST" action="{{ route('participant.payments.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="activity_public_id" value="{{ $activity->public_id }}">
                    <input type="hidden" name="enrollment_public_id" value="{{ $enrollment->public_id }}">
                    <input type="hidden" name="amount" value="{{ $activity->cost }}">
                    <input type="hidden" name="currency" value="MXN">
                    <div class="space-y-5">
                        <label class="block text-sm font-semibold text-slate-700">Método de pago
                            <select class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3" name="payment_method" x-model="method">
                                <option value="tarjeta">Tarjeta de crédito o débito (demostración)</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="deposito">Depósito</option>
                            </select>
                        </label>
                        <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" x-show="method === 'tarjeta'" x-transition>
                            <div class="flex items-center justify-between"><span class="text-sm font-bold text-slate-900">Datos de la tarjeta</span><span class="rounded-md bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700">VISTA PREVIA</span></div>
                            <label class="block text-xs font-semibold text-slate-700">Nombre del titular
                                <input class="mt-1.5 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 text-slate-950 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" type="text" placeholder="NOMBRE COMO APARECE EN LA TARJETA" autocomplete="off">
                            </label>
                            <label class="block text-xs font-semibold text-slate-700">Número de tarjeta
                                <input class="mt-1.5 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 font-mono text-slate-950 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" type="text" inputmode="numeric" maxlength="19" placeholder="0000 0000 0000 0000" autocomplete="off">
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="block text-xs font-semibold text-slate-700">Caducidad<input class="mt-1.5 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 font-mono text-slate-950 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" type="text" inputmode="numeric" maxlength="5" placeholder="MM/AA" autocomplete="off"></label>
                                <label class="block text-xs font-semibold text-slate-700">Código de seguridad<input class="mt-1.5 w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2.5 font-mono text-slate-950 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100" type="password" inputmode="numeric" maxlength="4" placeholder="CVV" autocomplete="off"></label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="font-bold text-slate-900">Comprobante de pago</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Para esta demostración, adjunta un PDF, JPG o PNG. Educación Continua lo revisará manualmente.</p>
                        <label class="mt-5 block text-sm font-semibold text-slate-700">Seleccionar archivo<input class="mt-2 block w-full rounded-xl border border-dashed border-slate-300 bg-white p-3 text-sm" type="file" name="proof" accept="application/pdf,image/jpeg,image/png" required></label>
                        <div class="mt-auto pt-6"><button class="w-full rounded-xl bg-amber-700 px-5 py-3 font-semibold text-white transition hover:bg-amber-600" type="submit">Simular pago y enviar comprobante</button><p class="mt-3 text-center text-xs text-slate-500">No se realizará ningún cargo real.</p></div>
                    </div>
                </form>
            </section>
        @endif
    </x-portal-page>
@endsection
