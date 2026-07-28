<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Plataforma Institucional de Formación')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    @php($isProfessorPortal = auth()->user()?->hasRole('Profesor') ?? false)
    @php($isStudentPortal = auth()->user()?->hasRole('Alumno') ?? false)
    @php($isAcademicPortal = $isProfessorPortal || $isStudentPortal)
    @php($academicRole = $isProfessorPortal ? 'Profesor' : 'Alumno')

    <header class="bg-slate-900 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center gap-4">
                @if($isAcademicPortal)
                    <img
                        class="h-14 w-auto object-contain"
                        src="{{ asset('img/LogoUNAQ.webp') }}"
                        alt="Logo de la Universidad Aeronáutica en Querétaro"
                    >
                @endif

                <div>
                    <p class="text-xs uppercase tracking-widest text-slate-300">Universidad</p>
                    <p class="font-semibold">@yield('portal-name', 'Plataforma de Formación')</p>
                </div>
            </div>
            @auth
                @if($isAcademicPortal)
                    <details class="group relative">
                        <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl border border-slate-700 px-3 py-2 transition hover:border-blue-500/60 hover:bg-blue-500/10 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-lg shadow-blue-950/40">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                                <span class="block text-xs text-slate-400">{{ $academicRole }}</span>
                            </span>
                            <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <div class="absolute right-0 z-50 mt-3 w-72 overflow-hidden rounded-xl border border-slate-200 bg-white text-slate-700 shadow-2xl shadow-slate-950/20">
                            <div class="border-b border-slate-100 bg-slate-50 px-5 py-4">
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-600">Tu perfil</p>
                                <p class="mt-2 truncate font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="mt-1 truncate text-sm text-slate-500">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="space-y-3 px-5 py-4 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-slate-500">Rol</span>
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 font-semibold text-blue-700">{{ $academicRole }}</span>
                                </div>
                                @if($isProfessorPortal)
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-slate-500">Área</span>
                                        <span class="truncate font-medium text-slate-700">{{ auth()->user()->area?->name ?? 'Sin área asignada' }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-slate-500">Estado</span>
                                        <span class="font-semibold text-emerald-700">Activo</span>
                                    </div>
                                @endif
                            </div>

                            <form class="border-t border-slate-100 p-3" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700" type="submit">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </details>
                @else
                    <span class="text-sm text-slate-200">{{ auth()->user()->name }}</span>
                @endif
            @endauth
        </div>
    </header>

    <div @class([
        'grid min-h-[calc(100vh-132px)] gap-6 py-6',
        'mx-auto max-w-7xl px-6 md:grid-cols-[240px_1fr]' => ! $isAcademicPortal,
        'w-full px-4 md:grid-cols-[260px_1fr]' => $isAcademicPortal,
    ])>
        <aside @class([
            'rounded-xl p-4 shadow-sm',
            'bg-white' => ! $isAcademicPortal,
            'border border-slate-200 bg-white p-4 text-slate-700 shadow-lg shadow-slate-200/60' => $isAcademicPortal,
        ])>
            <nav class="space-y-2 text-sm">
                @yield('navigation')
            </nav>
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

    <footer class="border-t bg-white px-6 py-4 text-center text-sm text-slate-500">
        Plataforma Institucional de Formación, Evidencias y Microcredenciales
    </footer>
</body>
</html>
