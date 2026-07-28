@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$activity->name" description="Seguimiento docente de la edición asignada.">
        <div class="grid gap-6 xl:grid-cols-2">
            <section>
                <h2 class="font-semibold text-slate-900">Participantes</h2>
                <div class="mt-4 space-y-3">
                    @forelse($activity->enrollments as $enrollment)
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 p-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $enrollment->user?->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $enrollment->user?->email }}</p>
                            </div>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ ucfirst($enrollment->status) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No hay participantes inscritos.</p>
                    @endforelse
                </div>
            </section>

            <section>
                <h2 class="font-semibold text-slate-900">Evidencias recibidas</h2>
                <div class="mt-4 space-y-3">
                    @forelse($activity->evidences as $evidence)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $evidence->title }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $evidence->user?->name }}</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($evidence->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No se han recibido evidencias.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </x-portal-page>
@endsection
