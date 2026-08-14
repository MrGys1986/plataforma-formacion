@extends('layouts.personal')

@section('content')
    <x-portal-page :title="'Asistencia de '.$activity->name" description="Consulta el historial de asistencia de cada participante inscrito.">
        @include('personal.courses._management-nav')

        <div class="space-y-4">
            @forelse ($attendanceRecords as $enrollment)
                @php
                    $sessions = $enrollment->attendanceRecords;
                    $attended = $sessions->where('attended', true)->count();
                    $attendanceRate = $sessions->count() > 0 ? round(($attended / $sessions->count()) * 100) : 0;
                @endphp
                <article class="rounded-xl border border-slate-200 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">{{ mb_strtoupper(mb_substr($enrollment->user?->name ?? '?', 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ $enrollment->user?->name ?? 'Usuario no disponible' }}</p>
                                <p class="truncate text-sm text-slate-500">{{ $enrollment->user?->email ?? 'Sin correo' }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $sessions->count() }} sesiones</span>
                            <span @class([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $attendanceRate >= 80,
                                'bg-amber-100 text-amber-700' => $attendanceRate > 0 && $attendanceRate < 80,
                                'bg-slate-100 text-slate-600' => $attendanceRate === 0,
                            ])>{{ $attendanceRate }}% asistencia</span>
                        </div>
                    </div>

                    @if ($sessions->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                            @foreach ($sessions->sortByDesc('session_date')->take(8) as $record)
                                <span @class([
                                    'rounded-lg border px-3 py-2 text-xs font-semibold',
                                    'border-emerald-200 bg-emerald-50 text-emerald-700' => $record->attended,
                                    'border-red-200 bg-red-50 text-red-700' => ! $record->attended,
                                ])>
                                    {{ $record->session_date?->format('d/m/Y') }} · {{ $record->attended ? 'Presente' : 'Ausente' }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-500">Aún no hay sesiones de asistencia registradas para este participante.</p>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay participantes inscritos en esta actividad.</div>
            @endforelse
        </div>

        @if ($attendanceRecords->hasPages())<div class="mt-6">{{ $attendanceRecords->links() }}</div>@endif
    </x-portal-page>
@endsection
