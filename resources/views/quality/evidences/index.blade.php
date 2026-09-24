@extends('layouts.portal')

@section('content')
    <x-portal-page title="Evidencias" description="Expedientes académicos y avance de su proceso de validación.">
        @php
            $pagePending = $evidences->where('status', 'pendiente')->count();
            $pageValidated = $evidences->where('status', 'validada')->count();
            $pageRejected = $evidences->where('status', 'rechazada')->count();
        @endphp
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-quality-stat label="Total de evidencias" :value="$evidences->count()" detail="Expedientes visibles" />
            <x-quality-stat label="Pendientes" :value="$pagePending" detail="En esta página" tone="amber" />
            <x-quality-stat label="Validadas" :value="$pageValidated" detail="En esta página" tone="emerald" />
            <x-quality-stat label="Con observaciones" :value="$pageRejected" detail="En esta página" tone="rose" />
        </div>

        <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Evidencia</th><th class="px-5 py-3">Participante</th><th class="px-5 py-3">Actividad</th><th class="px-5 py-3">Fecha</th><th class="px-5 py-3">Estado</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($evidences as $evidence)
                        <tr class="hover:bg-slate-50"><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $evidence->title }}</p><p class="mt-1 text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $evidence->evidence_type ?? 'documento')) }}</p></td><td class="px-5 py-4"><p class="font-medium text-slate-800">{{ $evidence->user?->name ?? 'Usuario no disponible' }}</p><p class="text-xs text-slate-500">{{ $evidence->user?->email }}</p></td><td class="px-5 py-4 text-slate-600">{{ $evidence->activity?->name ?? 'Sin actividad' }}</td><td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $evidence->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</td><td class="px-5 py-4"><span @class(['rounded-full px-2.5 py-1 text-xs font-semibold','bg-amber-100 text-amber-700' => $evidence->status === 'pendiente','bg-emerald-100 text-emerald-700' => $evidence->status === 'validada','bg-rose-100 text-rose-700' => $evidence->status === 'rechazada','bg-slate-100 text-slate-600' => !in_array($evidence->status, ['pendiente','validada','rechazada'], true)])>{{ ucfirst($evidence->status ?? 'Sin estado') }}</span></td></tr>
                    @empty
                        <tr><td class="px-6 py-12 text-center text-slate-500" colspan="5"><p class="font-semibold text-slate-700">No hay evidencias registradas.</p><p class="mt-1 text-sm">Las entregas aparecerán aquí cuando los participantes las envíen.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-portal-page>
@endsection
