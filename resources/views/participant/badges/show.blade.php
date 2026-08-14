@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$microcredential->name" description="Detalle de tu insignia digital institucional.">
        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
            <section class="rounded-2xl bg-gradient-to-br from-slate-950 via-blue-950 to-violet-950 p-8 text-center text-white shadow-xl">
                <x-course-badge-emblem :badge="$microcredential" size="lg" />
                <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-blue-200">Universidad</p>
                <p class="mt-2 font-semibold">{{ $microcredential->name }}</p>
                <p class="mt-3 text-xs text-slate-300">ID {{ $microcredential->public_id }}</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Verificada · Emitida</span>
                <p class="mt-5 text-slate-700">{{ $microcredential->description }}</p>

                <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Titular</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $microcredential->user?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Fecha de emisión</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $microcredential->issued_at?->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Habilidades</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $microcredential->skills ?: 'No especificadas' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Competencias</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $microcredential->competencies ?: 'No especificadas' }}</dd>
                    </div>
                </dl>

                <a class="mt-7 inline-flex rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                   href="{{ route('public.badges.verify', $microcredential) }}" target="_blank">
                    Abrir verificación pública
                </a>
            </section>
        </div>
    </x-portal-page>
@endsection
