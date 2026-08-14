@extends('layouts.area-manager')

@section('content')
    <x-portal-page
        title="Inscripciones del área"
        description="{{ $selectedActivity ? 'Inscripciones de '.$selectedActivity->name : 'Inscripciones vinculadas a actividades de tu área' }}: {{ $enrollments->total() }}"
    >
        @if ($selectedActivity)
            <div class="mb-5 flex flex-col gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-blue-800">Mostrando únicamente las inscripciones de <span class="font-semibold">{{ $selectedActivity->name }}</span>.</p>
                <a class="text-sm font-semibold text-blue-700 hover:text-blue-900" href="{{ route('area-manager.enrollments.index') }}">Ver todas</a>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($enrollments as $enrollment)
                <article class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                    {{ mb_strtoupper(mb_substr($enrollment->user?->name ?? '?', 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">{{ $enrollment->user?->name ?? 'Usuario no disponible' }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $enrollment->user?->email ?? 'Sin correo' }}</p>
                                </div>
                            </div>

                            <div class="mt-4 border-l-2 border-blue-200 pl-4">
                                <p class="font-semibold text-slate-800">{{ $enrollment->activity?->name ?? 'Actividad no disponible' }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $enrollment->activity?->activityType?->name ?? 'Actividad' }}
                                    <span aria-hidden="true">·</span>
                                    {{ ucfirst($enrollment->activity?->modality ?? 'sin modalidad') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 lg:max-w-sm lg:justify-end">
                            <span @class([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $enrollment->status === 'aprobada',
                                'bg-amber-100 text-amber-700' => $enrollment->status === 'solicitada',
                                'bg-red-100 text-red-700' => $enrollment->status === 'rechazada',
                                'bg-slate-100 text-slate-600' => ! in_array($enrollment->status, ['aprobada', 'solicitada', 'rechazada'], true),
                            ])>
                                Inscripción: {{ ucfirst($enrollment->status) }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                Avance: {{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-2 border-t border-slate-100 pt-4 text-sm text-slate-500 sm:grid-cols-2 lg:grid-cols-3">
                        <p><span class="font-semibold text-slate-600">Solicitud:</span> {{ $enrollment->requested_at?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                        <p><span class="font-semibold text-slate-600">Área:</span> {{ $enrollment->activity?->area?->name ?? $enrollment->user?->area?->name ?? 'Sin área' }}</p>
                        <p><span class="font-semibold text-slate-600">Calificación:</span> {{ $enrollment->final_score ?? 'Pendiente' }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay inscripciones en las actividades de tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las inscripciones aparecerán aquí cuando un participante se vincule con una actividad del área.</p>
                </div>
            @endforelse
        </div>

        @if ($enrollments->hasPages())
            <div class="mt-6">{{ $enrollments->links() }}</div>
        @endif
    </x-portal-page>
@endsection
