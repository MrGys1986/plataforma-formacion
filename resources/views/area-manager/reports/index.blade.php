@extends('layouts.area-manager')

@section('content')
    <x-portal-page title="Reportes del área" description="Resumen actualizado de formación, participación y evidencias de tu área.">
        @php
            $approvalRate = $training['total'] > 0
                ? round(($training['aprobadas'] / $training['total']) * 100)
                : 0;
            $validationRate = $evidences['total'] > 0
                ? round(($evidences['validadas'] / $evidences['total']) * 100)
                : 0;
        @endphp

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="{{ route('area-manager.activities.index') }}">
                <p class="text-sm font-semibold text-slate-500">Actividades</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activities->count() }}</p>
                <p class="mt-2 text-xs font-semibold text-blue-700">Consultar actividades →</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="{{ route('area-manager.participants.index') }}">
                <p class="text-sm font-semibold text-slate-500">Usuarios del área</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $participantsCount }}</p>
                <p class="mt-2 text-xs font-semibold text-blue-700">Consultar participantes →</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="{{ route('area-manager.enrollments.index') }}">
                <p class="text-sm font-semibold text-slate-500">Inscripciones</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $training['total'] }}</p>
                <p class="mt-2 text-xs font-semibold text-blue-700">{{ $approvalRate }}% aprobadas</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="{{ route('area-manager.evidences.index') }}">
                <p class="text-sm font-semibold text-slate-500">Evidencias</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $evidences['total'] }}</p>
                <p class="mt-2 text-xs font-semibold text-blue-700">{{ $validationRate }}% validadas</p>
            </a>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Estado de inscripciones</h2>
                    <span class="text-sm text-slate-500">Total: {{ $training['total'] }}</span>
                </div>
                <div class="mt-5 space-y-4 text-sm">
                    @foreach ([
                        ['label' => 'Aprobadas', 'value' => $training['aprobadas'], 'color' => 'bg-emerald-500'],
                        ['label' => 'Solicitadas', 'value' => $training['solicitadas'], 'color' => 'bg-amber-500'],
                        ['label' => 'Rechazadas', 'value' => $training['rechazadas'], 'color' => 'bg-red-500'],
                        ['label' => 'Completadas', 'value' => $training['completadas'], 'color' => 'bg-blue-500'],
                    ] as $item)
                        @php($percentage = $training['total'] > 0 ? round(($item['value'] / $training['total']) * 100) : 0)
                        <div>
                            <div class="mb-1.5 flex items-center justify-between gap-3">
                                <span class="font-medium text-slate-600">{{ $item['label'] }}</span>
                                <span class="font-semibold text-slate-900">{{ $item['value'] }} <span class="font-normal text-slate-400">({{ $percentage }}%)</span></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Estado de evidencias</h2>
                    <span class="text-sm text-slate-500">Total: {{ $evidences['total'] }}</span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['label' => 'Pendientes', 'value' => $evidences['pendientes'], 'classes' => 'bg-amber-50 text-amber-700'],
                        ['label' => 'Validadas', 'value' => $evidences['validadas'], 'classes' => 'bg-emerald-50 text-emerald-700'],
                        ['label' => 'Rechazadas', 'value' => $evidences['rechazadas'], 'classes' => 'bg-red-50 text-red-700'],
                    ] as $item)
                        <div class="rounded-xl p-4 text-center {{ $item['classes'] }}">
                            <p class="text-2xl font-bold">{{ $item['value'] }}</p>
                            <p class="mt-1 text-xs font-semibold">{{ $item['label'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <span class="font-semibold text-slate-800">Seguimiento:</span>
                    {{ $evidences['pendientes'] }} {{ $evidences['pendientes'] === 1 ? 'evidencia requiere' : 'evidencias requieren' }} revisión.
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-xl border border-slate-200">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Detalle por actividad</h2>
                <p class="mt-1 text-sm text-slate-500">Comparativo de inscripciones y evidencias registradas.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Actividad</th>
                            <th class="px-5 py-3 text-center font-semibold">Inscripciones</th>
                            <th class="px-5 py-3 text-center font-semibold">Aprobadas</th>
                            <th class="px-5 py-3 text-center font-semibold">Solicitadas</th>
                            <th class="px-5 py-3 text-center font-semibold">Evidencias</th>
                            <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($activities as $activity)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ $activity->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $activity->activityType?->name ?? 'Actividad' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center font-semibold text-slate-700">{{ $activity->enrollments_count }}</td>
                                <td class="px-5 py-4 text-center text-emerald-700">{{ $activity->approved_enrollments_count }}</td>
                                <td class="px-5 py-4 text-center text-amber-700">{{ $activity->requested_enrollments_count }}</td>
                                <td class="px-5 py-4 text-center text-slate-700">
                                    {{ $activity->evidences_count }}
                                    <span class="block text-xs text-slate-400">{{ $activity->validated_evidences_count }} validadas</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a class="font-semibold text-blue-700 hover:text-blue-900" href="{{ route('area-manager.enrollments.index', ['activity' => $activity->id]) }}">Inscripciones</a>
                                        <a class="font-semibold text-blue-700 hover:text-blue-900" href="{{ route('area-manager.evidences.index', ['activity' => $activity->id]) }}">Evidencias</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-5 py-10 text-center text-slate-500" colspan="6">No hay actividades para generar el reporte.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </x-portal-page>
@endsection
