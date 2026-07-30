@props([
    'stats' => [],
    'actions' => [],
    'attention' => [],
])

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $stat)
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm font-medium text-slate-600">{{ $stat['label'] }}</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
            @if(filled($stat['description'] ?? null))
                <p class="mt-2 text-xs text-slate-500">{{ $stat['description'] }}</p>
            @endif
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
    <section>
        <h2 class="font-semibold text-slate-900">Accesos rápidos</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            @foreach($actions as $action)
                <a class="group rounded-xl border border-slate-200 bg-white p-4 transition hover:border-blue-300 hover:shadow-md" href="{{ $action['url'] }}">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900 group-hover:text-blue-700">{{ $action['label'] }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $action['description'] }}</p>
                        </div>
                        <span class="text-lg text-blue-600" aria-hidden="true">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-slate-50/70 p-5">
        <h2 class="font-semibold text-slate-900">Atención requerida</h2>
        <div class="mt-3 space-y-3">
            @forelse($attention as $item)
                <a class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 bg-white px-4 py-3 transition hover:border-blue-200" href="{{ $item['url'] }}">
                    <span class="text-sm font-medium text-slate-700">{{ $item['label'] }}</span>
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">{{ $item['value'] }}</span>
                </a>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 bg-white px-4 py-7 text-center text-sm text-slate-500">
                    No hay pendientes registrados.
                </div>
            @endforelse
        </div>
    </section>
</div>
