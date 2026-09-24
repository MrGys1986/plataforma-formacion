<div class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Actividad</p>
        <p class="mt-1 truncate font-semibold text-slate-900">{{ $activity->name }}</p>
    </div>
    <nav class="flex flex-wrap gap-2 text-sm" aria-label="Gestión de la actividad">
        @foreach ([
            ['label' => 'Resumen', 'route' => 'personal.courses.show'],
            ['label' => 'Participantes', 'route' => 'personal.courses.participants'],
            ['label' => 'Asistencia', 'route' => 'personal.attendance.index'],
            ['label' => 'Evidencias', 'route' => 'personal.evidences.index'],
            ['label' => 'Evaluaciones', 'route' => 'personal.evaluations.index'],
            ['label' => 'Constancias', 'route' => 'personal.certificates.index'],
        ] as $item)
            <a
                @class([
                    'rounded-lg border px-3 py-2 font-semibold transition',
                    'border-blue-200 bg-blue-100 text-blue-800' => request()->routeIs($item['route']),
                    'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:text-blue-700' => ! request()->routeIs($item['route']),
                ])
                href="{{ route($item['route'], $activity) }}"
            >{{ $item['label'] }}</a>
        @endforeach
    </nav>
</div>
