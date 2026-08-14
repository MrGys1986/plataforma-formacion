@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$activity->name" description="Seguimiento, evaluación y constancias de los participantes inscritos.">
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Revisa la información indicada:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:flex-row" method="GET">
            <label class="sr-only" for="student-search">Buscar alumno</label>
            <input class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" id="student-search" name="q" value="{{ $search }}" placeholder="Buscar alumno por nombre o correo…">
            <button class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-600" type="submit">Buscar</button>
            @if($search !== '')
                <a class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-100" href="{{ route('participant.professor.teaching.show', $activity) }}">Limpiar</a>
            @endif
        </form>

        <section class="mb-8">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Solicitudes de inscripción</h2>
                    <p class="mt-1 text-sm text-slate-500">Aprueba o rechaza a las personas que desean ingresar al curso.</p>
                </div>
                <span @class([
                    'rounded-full px-3 py-1 text-xs font-semibold',
                    'bg-amber-100 text-amber-700' => $pendingEnrollments->isNotEmpty(),
                    'bg-slate-100 text-slate-600' => $pendingEnrollments->isEmpty(),
                ])>{{ $pendingEnrollments->count() }} pendientes</span>
            </div>

            <div class="space-y-3">
                @forelse($pendingEnrollments as $enrollment)
                    <article class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-950">{{ $enrollment->user?->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $enrollment->user?->email }}</p>
                                <p class="mt-2 text-xs font-medium text-amber-700">Solicitud recibida {{ $enrollment->requested_at?->format('d/m/Y H:i') ?? $enrollment->created_at?->format('d/m/Y H:i') }}</p>
                                @if($activity->requires_payment && in_array($enrollment->payment_status, ['validado', 'pagado'], true))
                                    <p class="mt-2 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Pago y comprobante validados</p>
                                @endif
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                                <form method="POST" action="{{ route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="aprobada">
                                    <button class="w-full rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500" type="submit">Aprobar inscripción</button>
                                </form>

                                <form class="flex flex-col gap-2 sm:flex-row" method="POST" action="{{ route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rechazada">
                                    <label class="sr-only" for="rejection-{{ $enrollment->id }}">Motivo del rechazo</label>
                                    <input class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" id="rejection-{{ $enrollment->id }}" name="reason" maxlength="1000" placeholder="Motivo del rechazo" required>
                                    <button class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500" type="submit">Rechazar</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">No hay solicitudes pendientes{{ $search !== '' ? ' para esta búsqueda' : '' }}.</p>
                @endforelse
            </div>
        </section>

        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-slate-900">Participantes inscritos</h2>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $enrollments->count() }} resultados</span>
        </div>

        <div class="space-y-5">
            @forelse($enrollments as $enrollment)
                @php($certificate = $enrollment->certificates->first())
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-950">{{ $enrollment->user?->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $enrollment->user?->email }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700">{{ ucfirst($enrollment->status) }}</span>
                            <span @class([
                                'rounded-full px-3 py-1',
                                'bg-emerald-100 text-emerald-700' => $enrollment->completion_status === 'completado',
                                'bg-amber-100 text-amber-700' => $enrollment->completion_status !== 'completado',
                            ])>{{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}</span>
                        </div>
                    </header>

                    <div class="grid gap-6 p-5 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,.65fr)]">
                        <section>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <h4 class="font-semibold text-slate-900">Evidencias del alumno</h4>
                                <span class="text-xs font-medium text-slate-500">{{ $enrollment->evidences->count() }} archivos</span>
                            </div>

                            <div class="space-y-3">
                                @forelse($enrollment->evidences as $evidence)
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $evidence->title }}</p>
                                                <p class="mt-1 text-xs text-slate-500">{{ ucfirst($evidence->evidence_type) }}</p>
                                            </div>
                                            <span @class([
                                                'rounded-full px-3 py-1 text-xs font-semibold',
                                                'bg-emerald-100 text-emerald-700' => $evidence->status === 'validada',
                                                'bg-rose-100 text-rose-700' => $evidence->status === 'rechazada',
                                                'bg-amber-100 text-amber-700' => $evidence->status === 'pendiente',
                                            ])>{{ ucfirst($evidence->status) }}</span>
                                        </div>

                                        @if($evidence->fileUpload)
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @if(in_array($evidence->fileUpload->mime_type, ['application/pdf', 'image/png', 'image/jpeg'], true))
                                                    <a class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700" href="{{ $evidence->fileUpload->temporaryPreviewUrl() }}" target="_blank" rel="noopener">Vista previa</a>
                                                @endif
                                                <a class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" href="{{ $evidence->fileUpload->temporaryDownloadUrl() }}">Descargar</a>
                                            </div>
                                        @endif

                                        <form class="mt-4 border-t border-slate-100 pt-4" method="POST" action="{{ route('participant.professor.teaching.evidences.review', [$activity, $evidence]) }}">
                                            @csrf
                                            <textarea class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" name="reason" rows="2" maxlength="1000" placeholder="Observaciones (obligatorias al rechazar)"></textarea>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <button class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500" type="submit" name="status" value="validada">Validar evidencia</button>
                                                <button class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500" type="submit" name="status" value="rechazada">Rechazar</button>
                                            </div>
                                        </form>
                                    </div>
                                @empty
                                    <p class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">Este alumno todavía no ha subido evidencias.</p>
                                @endforelse
                            </div>
                        </section>

                        <aside class="space-y-5">
                            <section class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                                <h4 class="font-semibold text-slate-900">Evaluación del alumno</h4>
                                <form class="mt-4 space-y-3" method="POST" action="{{ route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label class="block">
                                        <span class="mb-1 block text-sm font-medium text-slate-700">Calificación final</span>
                                        <input class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" type="number" name="final_score" value="{{ $enrollment->final_score }}" min="0" max="100" step="0.01" required>
                                    </label>
                                    <label class="block">
                                        <span class="mb-1 block text-sm font-medium text-slate-700">Avance</span>
                                        <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="completion_status" required>
                                            @foreach(['no_iniciado' => 'No iniciado', 'en_progreso' => 'En progreso', 'completado' => 'Completado', 'no_aprobado' => 'No aprobado'] as $value => $label)
                                                <option value="{{ $value }}" @selected($enrollment->completion_status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Guardar evaluación</button>
                                </form>
                            </section>

                            <section class="rounded-xl border border-slate-200 p-4">
                                <h4 class="font-semibold text-slate-900">Constancia del curso</h4>
                                @if($enrollment->completion_status === 'completado')
                                    @if($certificate?->fileUpload)
                                        <div class="mt-3 flex items-center justify-between gap-3 rounded-lg bg-emerald-50 px-3 py-2 text-sm">
                                            <span class="font-medium text-emerald-700">Constancia emitida</span>
                                            <a class="font-semibold text-emerald-800 underline" href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}">Ver</a>
                                        </div>
                                    @endif
                                    <form class="mt-3 space-y-3" method="POST" action="{{ route('participant.professor.teaching.certificates.store', [$activity, $enrollment]) }}" enctype="multipart/form-data">
                                        @csrf
                                        <label class="sr-only" for="certificate-{{ $enrollment->id }}">Constancia de {{ $enrollment->user?->name }}</label>
                                        <input class="block w-full text-sm text-slate-600 file:mb-2 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700" id="certificate-{{ $enrollment->id }}" type="file" name="certificate" accept="application/pdf" required>
                                        <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit">{{ $certificate ? 'Reemplazar constancia' : 'Subir constancia' }}</button>
                                    </form>
                                @else
                                    <p class="mt-2 text-sm text-slate-500">La carga se habilita al marcar el curso como completado.</p>
                                @endif
                            </section>
                        </aside>
                    </div>
                </article>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">No se encontraron alumnos inscritos con esa búsqueda.</p>
            @endforelse
        </div>
    </x-portal-page>
@endsection
