@extends('layouts.personal')

@section('content')
    <div class="space-y-6">
        <section class="overflow-hidden rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-300/40">
            <div class="relative px-6 py-7 sm:px-8 sm:py-9">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-600/20 blur-3xl" aria-hidden="true"></div>
                <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.16em]">
                            <span class="rounded-full bg-blue-500/20 px-3 py-1 text-blue-200">{{ $activity->activityType?->name ?? 'Actividad' }}</span>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-slate-200">{{ ucfirst(str_replace('_', ' ', $activity->status)) }}</span>
                        </div>
                        <h1 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">{{ $activity->name }}</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                            {{ $activity->description ?: 'Gestiona el seguimiento académico y la participación de esta actividad.' }}
                        </p>
                    </div>

                    <div class="grid shrink-0 grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-xs text-slate-400">Modalidad</p>
                            <p class="mt-1 font-semibold">{{ ucfirst($activity->modality) }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-xs text-slate-400">Duración</p>
                            <p class="mt-1 font-semibold">{{ rtrim(rtrim(number_format((float) $activity->duration_hours, 2), '0'), '.') }} horas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid border-t border-white/10 bg-slate-950/30 text-sm sm:grid-cols-2 lg:grid-cols-4">
                <div class="border-white/10 px-6 py-4 sm:border-r">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Área</p>
                    <p class="mt-1 font-medium text-white">{{ $activity->area?->name ?? 'Sin área asignada' }}</p>
                </div>
                <div class="border-t border-white/10 px-6 py-4 sm:border-r sm:border-t-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Inicio</p>
                    <p class="mt-1 font-medium text-white">{{ $activity->start_date?->format('d/m/Y') ?? 'Por definir' }}</p>
                </div>
                <div class="border-t border-white/10 px-6 py-4 lg:border-r lg:border-t-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Finalización</p>
                    <p class="mt-1 font-medium text-white">{{ $activity->end_date?->format('d/m/Y') ?? 'Por definir' }}</p>
                </div>
                <div class="border-t border-white/10 px-6 py-4 lg:border-t-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Cupo</p>
                    <p class="mt-1 font-medium text-white">{{ $activity->enrollments_count }} / {{ $activity->max_capacity ?? 'Sin límite' }}</p>
                </div>
            </div>
        </section>

        <section>
            <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Gestión de la actividad</h2>
                    <p class="mt-1 text-sm text-slate-500">Accede al seguimiento de participantes y resultados.</p>
                </div>
                <a class="text-sm font-semibold text-blue-700 hover:text-blue-900" href="{{ route('personal.courses.index') }}">← Volver a mis actividades</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg" href="{{ route('personal.courses.participants', $activity) }}">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-xl text-blue-700">👥</span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $activity->enrollments_count }}</span>
                    </div>
                    <h3 class="mt-5 font-semibold text-slate-900 group-hover:text-blue-700">Participantes</h3>
                    <p class="mt-2 text-sm leading-5 text-slate-500">{{ $activity->approved_enrollments_count }} inscripciones aprobadas.</p>
                    <p class="mt-4 text-sm font-semibold text-blue-700">Administrar →</p>
                </a>

                <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg" href="{{ route('personal.attendance.index', $activity) }}">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-700">✓</span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $attendanceRecordsCount }}</span>
                    </div>
                    <h3 class="mt-5 font-semibold text-slate-900 group-hover:text-blue-700">Asistencia</h3>
                    <p class="mt-2 text-sm leading-5 text-slate-500">Registros de asistencia capturados.</p>
                    <p class="mt-4 text-sm font-semibold text-blue-700">Consultar →</p>
                </a>

                <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg" href="{{ route('personal.evidences.index', $activity) }}">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-700">▤</span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $activity->evidences_count }}</span>
                    </div>
                    <h3 class="mt-5 font-semibold text-slate-900 group-hover:text-blue-700">Evidencias</h3>
                    <p class="mt-2 text-sm leading-5 text-slate-500">{{ $activity->pending_evidences_count }} pendientes de seguimiento.</p>
                    <p class="mt-4 text-sm font-semibold text-blue-700">Revisar →</p>
                </a>

                <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg" href="{{ route('personal.evaluations.index', $activity) }}">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-xl text-violet-700">★</span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $activity->evaluations_count }}</span>
                    </div>
                    <h3 class="mt-5 font-semibold text-slate-900 group-hover:text-blue-700">Evaluaciones</h3>
                    <p class="mt-2 text-sm leading-5 text-slate-500">Instrumentos configurados para la actividad.</p>
                    <p class="mt-4 text-sm font-semibold text-blue-700">Consultar →</p>
                </a>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h2 class="font-semibold text-slate-900">Seguimiento general</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Inscripciones</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $activity->enrollments_count }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Evidencias</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $activity->evidences_count }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Constancias</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $activity->certificates_count }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Próxima acción</p>
                @if ($activity->pending_evidences_count > 0)
                    <p class="mt-3 font-semibold text-slate-900">Hay {{ $activity->pending_evidences_count }} {{ $activity->pending_evidences_count === 1 ? 'evidencia pendiente' : 'evidencias pendientes' }}.</p>
                    <a class="mt-4 inline-flex rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800" href="{{ route('personal.evidences.index', $activity) }}">Revisar evidencias</a>
                @else
                    <p class="mt-3 font-semibold text-slate-900">El seguimiento está al día.</p>
                    <p class="mt-2 text-sm text-slate-600">No hay evidencias pendientes en este momento.</p>
                @endif
            </div>
        </section>
    </div>
@endsection
