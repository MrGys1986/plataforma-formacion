@extends('layouts.area-manager')

@section('content')
    <x-portal-page
        title="Actividades del área"
        description="Actividades registradas y vinculadas con tu área: {{ $activities->total() }}"
    >
        <div class="grid gap-5 xl:grid-cols-2">
            @forelse ($activities as $activity)
                <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-600">
                                {{ $activity->activityType?->name ?? 'Actividad' }}
                            </p>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $activity->name }}</h2>
                        </div>
                        <span @class([
                            'w-fit shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-700' => in_array($activity->status, ['publicado', 'en_inscripcion', 'en_curso'], true),
                            'bg-blue-100 text-blue-700' => $activity->status === 'finalizado',
                            'bg-red-100 text-red-700' => $activity->status === 'cancelado',
                            'bg-slate-100 text-slate-600' => ! in_array($activity->status, ['publicado', 'en_inscripcion', 'en_curso', 'finalizado', 'cancelado'], true),
                        ])>
                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                        </span>
                    </div>

                    @if ($activity->description)
                        <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $activity->description }}</p>
                    @endif

                    <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="font-semibold text-slate-500">Instructor</dt>
                            <dd class="mt-1 text-slate-800">{{ $activity->instructor?->name ?? 'Sin instructor asignado' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Modalidad</dt>
                            <dd class="mt-1 text-slate-800">{{ ucfirst($activity->modality) }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Periodo</dt>
                            <dd class="mt-1 text-slate-800">
                                {{ $activity->start_date?->format('d/m/Y') ?? 'Por definir' }}
                                <span aria-hidden="true">–</span>
                                {{ $activity->end_date?->format('d/m/Y') ?? 'Por definir' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Duración</dt>
                            <dd class="mt-1 text-slate-800">{{ rtrim(rtrim(number_format((float) $activity->duration_hours, 2), '0'), '.') }} horas</dd>
                        </div>
                    </dl>

                    <div class="mt-auto flex flex-col gap-3 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $activity->enrollments_count }} inscripciones</p>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Cupo: {{ $activity->max_capacity ?? 'sin límite definido' }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a
                                class="inline-flex items-center justify-center rounded-lg border border-blue-200 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-50"
                                href="{{ route('area-manager.evidences.index', ['activity' => $activity->id]) }}"
                            >
                                Ver evidencias
                            </a>
                            <a
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                href="{{ route('area-manager.enrollments.index', ['activity' => $activity->id]) }}"
                            >
                                Ver inscripciones
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center xl:col-span-2">
                    <p class="font-semibold text-slate-700">No hay actividades registradas en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las actividades creadas y asignadas a esta área aparecerán aquí.</p>
                </div>
            @endforelse
        </div>

        @if ($activities->hasPages())
            <div class="mt-6">{{ $activities->links() }}</div>
        @endif
    </x-portal-page>
@endsection
