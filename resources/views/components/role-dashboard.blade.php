@props([
    'portal',
    'stats' => [],
    'actions' => [],
    'attention' => [],
    'recent' => [],
])

@php
    $pageTitle = $portal['title'];
    $pageDescription = $portal['description'];
    $pageContext = $portal['role'];
@endphp

<div class="space-y-6 sm:space-y-8">
    <section class="relative isolate overflow-hidden rounded-[1.75rem] bg-brand-950 px-5 py-7 text-white shadow-2xl shadow-brand-950/15 sm:px-8 sm:py-9 lg:px-10">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -right-20 -top-24 h-64 w-64 rounded-full bg-brand-500/25 blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-64 w-64 rounded-full bg-blue-400/15 blur-3xl"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>

        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-blue-100">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 ring-4 ring-emerald-400/15"></span>
                    {{ $pageContext }}
                </div>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $pageTitle }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100/80 sm:text-base">{{ $pageDescription }}</p>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.07] px-4 py-3 backdrop-blur-sm">
                @if(auth()->user()?->avatarFile)
                    <img class="h-11 w-11 rounded-xl object-cover ring-2 ring-white/20" src="{{ auth()->user()->avatarFile->optimizedImageUrl(132, 132) }}" alt="Fotografía de {{ auth()->user()->name }}">
                @else
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-base font-extrabold text-white">{{ mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1)) }}</span>
                @endif
                <div class="min-w-0">
                    <p class="text-xs text-blue-200">Sesión activa</p>
                    <p class="max-w-56 truncate text-sm font-bold text-white">{{ auth()->user()?->name }}</p>
                </div>
            </div>
        </div>
    </section>

    @if(session('status'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800" role="status">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 7"/></svg>
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800" role="alert">
            <p class="font-extrabold">Revisa la información capturada:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <section aria-labelledby="dashboard-summary">
        <div class="mb-4 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Resumen</p>
                <h2 id="dashboard-summary" class="mt-1 text-xl font-extrabold text-brand-950">Información relevante</h2>
            </div>
            <span class="hidden text-sm font-medium text-slate-500 sm:block">Datos actualizados</span>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($stats as $stat)
                @php
                    $tone = match($stat['tone'] ?? 'blue') {
                        'emerald' => ['bg-emerald-50 text-emerald-700 ring-emerald-100', 'bg-emerald-500'],
                        'amber' => ['bg-amber-50 text-amber-700 ring-amber-100', 'bg-amber-500'],
                        'violet' => ['bg-violet-50 text-violet-700 ring-violet-100', 'bg-violet-500'],
                        default => ['bg-brand-50 text-brand-700 ring-brand-100', 'bg-brand-500'],
                    };
                @endphp
                <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-950/[0.07]">
                    <span class="absolute inset-x-0 top-0 h-1 {{ $tone[1] }}"></span>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-slate-600">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-brand-950">{{ number_format($stat['value']) }}</p>
                        </div>
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 {{ $tone[0] }}">
                            <x-portal-nav-icon :name="$stat['icon'] ?? 'squares'" />
                        </span>
                    </div>
                    <p class="mt-4 text-xs leading-5 text-slate-500">{{ $stat['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.45fr)_minmax(18rem,0.75fr)]">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="dashboard-actions">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Navegación</p>
                <h2 id="dashboard-actions" class="mt-1 text-xl font-extrabold text-brand-950">Accesos rápidos</h2>
                <p class="mt-1 text-sm text-slate-500">Las opciones disponibles corresponden a tu rol.</p>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($actions as $action)
                    <a class="group flex min-h-32 items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition duration-200 hover:-translate-y-0.5 hover:border-brand-300 hover:bg-brand-50 hover:shadow-lg hover:shadow-brand-950/[0.06]" href="{{ $action['url'] }}">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 shadow-sm ring-1 ring-brand-100 transition group-hover:bg-brand-700 group-hover:text-white">
                            <x-portal-nav-icon :name="$action['icon'] ?? 'squares'" />
                        </span>
                        <span class="min-w-0">
                            <span class="flex items-center gap-2 font-extrabold text-slate-900 group-hover:text-brand-800">
                                {{ $action['label'] }}
                                <svg class="h-4 w-4 shrink-0 transition group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                            </span>
                            <span class="mt-1.5 block text-sm leading-5 text-slate-500">{{ $action['description'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-brand-100 bg-gradient-to-b from-brand-50 to-white shadow-sm" aria-labelledby="dashboard-attention">
            <div class="border-b border-brand-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-700 text-white shadow-md shadow-brand-700/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.6 2.7 18a1.5 1.5 0 0 0 1.3 2.25h16a1.5 1.5 0 0 0 1.3-2.25L13.7 4.6a1.95 1.95 0 0 0-3.4 0Z"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-brand-600">Seguimiento</p>
                        <h2 id="dashboard-attention" class="font-extrabold text-brand-950">Atención requerida</h2>
                    </div>
                </div>
            </div>

            <div class="space-y-3 p-5 sm:p-6">
                @forelse($attention as $item)
                    <a class="group flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:border-brand-300 hover:shadow-md" href="{{ $item['url'] }}">
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-brand-800">{{ $item['label'] }}</span>
                        <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-brand-100 px-2.5 py-1 text-xs font-extrabold text-brand-800">{{ number_format($item['value']) }}</span>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-emerald-50 px-5 py-7 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 7"/></svg>
                        </span>
                        <p class="mt-3 font-bold text-emerald-900">Todo al día</p>
                        <p class="mt-1 text-sm text-emerald-700">No hay pendientes registrados.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    @if(count($recent))
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="dashboard-recent">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Tu actividad</p>
                    <h2 id="dashboard-recent" class="mt-1 text-xl font-extrabold text-brand-950">Inscripciones recientes</h2>
                </div>
                <a class="text-sm font-bold text-brand-700 hover:text-brand-600" href="{{ route('participant.my-courses.index') }}">Ver toda mi formación →</a>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-2">
                @foreach($recent as $item)
                    <a class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-4 transition hover:border-brand-200 hover:bg-brand-50" href="{{ $item['url'] }}">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-slate-800">{{ $item['title'] }}</span>
                            @if($item['meta'])<span class="mt-1 block text-xs text-slate-500">{{ $item['meta'] }}</span>@endif
                        </span>
                        <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-bold text-brand-700 ring-1 ring-brand-100">{{ $item['status'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
