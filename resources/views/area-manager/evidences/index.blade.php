@extends('layouts.area-manager')

@section('content')
    <x-portal-page
        title="Evidencias del área"
        description="{{ $selectedActivity ? 'Evidencias de '.$selectedActivity->name : 'Evidencias entregadas en actividades de tu área' }}: {{ $evidences->total() }}"
    >
        @if ($selectedActivity)
            <div class="mb-5 flex flex-col gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-blue-800">Mostrando únicamente las evidencias de <span class="font-semibold">{{ $selectedActivity->name }}</span>.</p>
                <a class="text-sm font-semibold text-blue-700 hover:text-blue-900" href="{{ route('area-manager.evidences.index') }}">Ver todas</a>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($evidences as $evidence)
                <article class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                    {{ mb_strtoupper(mb_substr($evidence->user?->name ?? '?', 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900">{{ $evidence->user?->name ?? 'Usuario no disponible' }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $evidence->user?->email ?? 'Sin correo' }}</p>
                                </div>
                            </div>

                            <div class="mt-4 border-l-2 border-blue-200 pl-4">
                                <h2 class="font-semibold text-slate-900">{{ $evidence->title }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ $evidence->activity?->name ?? 'Actividad no disponible' }}</p>
                                @if ($evidence->description)
                                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $evidence->description }}</p>
                                @endif
                            </div>
                        </div>

                        <span @class([
                            'w-fit shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-amber-100 text-amber-700' => $evidence->status === 'pendiente',
                            'bg-emerald-100 text-emerald-700' => $evidence->status === 'validada',
                            'bg-red-100 text-red-700' => $evidence->status === 'rechazada',
                            'bg-slate-100 text-slate-600' => ! in_array($evidence->status, ['pendiente', 'validada', 'rechazada'], true),
                        ])>
                            {{ ucfirst($evidence->status) }}
                        </span>
                    </div>

                    <dl class="mt-5 grid gap-3 border-t border-slate-100 pt-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <dt class="font-semibold text-slate-500">Tipo</dt>
                            <dd class="mt-1 text-slate-800">{{ ucfirst(str_replace('_', ' ', $evidence->evidence_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Entrega</dt>
                            <dd class="mt-1 text-slate-800">{{ $evidence->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Evaluador</dt>
                            <dd class="mt-1 text-slate-800">{{ $evidence->assignedEvaluator?->name ?? 'Sin asignar' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Inscripción</dt>
                            <dd class="mt-1 text-slate-800">{{ ucfirst($evidence->enrollment?->status ?? 'sin relación') }}</dd>
                        </div>
                    </dl>

                    @if ($evidence->status === 'rechazada' && $evidence->rejection_reason)
                        <p class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700"><span class="font-semibold">Motivo:</span> {{ $evidence->rejection_reason }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay evidencias registradas en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las entregas de participantes inscritos en actividades del área aparecerán aquí.</p>
                </div>
            @endforelse
        </div>

        @if ($evidences->hasPages())
            <div class="mt-6">{{ $evidences->links() }}</div>
        @endif
    </x-portal-page>
@endsection
