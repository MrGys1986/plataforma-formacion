<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Acceso') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-32 top-0 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-cyan-400/15 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.12),transparent_34%)]"></div>
        </div>

        <main class="relative mx-auto flex min-h-screen max-w-7xl items-center px-4 py-4 sm:px-8 sm:py-8 lg:px-10">
            <div class="grid w-full overflow-hidden rounded-[1.5rem] border border-white/10 bg-white shadow-2xl shadow-blue-950/30 sm:rounded-[2rem] lg:grid-cols-[0.9fr_1.1fr]">
                <section class="relative overflow-hidden bg-gradient-to-br from-blue-800 via-blue-950 to-slate-950 p-6 text-white sm:p-8 lg:flex lg:min-h-[760px] lg:flex-col lg:p-10 xl:p-12">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute right-8 top-12 h-40 w-40 rounded-full border border-white/30"></div>
                        <div class="absolute right-20 top-24 h-40 w-40 rounded-full border border-white/20"></div>
                        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-400/30 blur-3xl"></div>
                    </div>

                    <div class="relative flex items-center justify-between gap-4">
                        <a class="inline-flex w-fit items-center" href="{{ route('home') }}">
                            <img class="h-14 w-auto sm:h-16 lg:h-20" src="{{ asset('img/LogoUNAQ.webp') }}" alt="Logo institucional">
                        </a>
                        <a class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white shadow-sm backdrop-blur-sm transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/20" href="{{ route('home') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                            </svg>
                            Volver al inicio
                        </a>
                    </div>

                    <div class="relative mx-auto max-w-md text-center lg:mt-8 lg:text-left">
                        <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-blue-100">
                            Plataforma institucional
                        </span>
                        <h1 class="mt-4 text-2xl font-bold leading-tight sm:text-3xl lg:mt-5 lg:text-4xl">
                            Formación y credenciales con acceso seguro.
                        </h1>
                        <p class="mt-3 text-sm leading-6 text-blue-100/80 sm:text-base lg:mt-4 lg:leading-7">
                            Consulta cursos, evidencias, evaluaciones y constancias desde un espacio centralizado para cada perfil.
                        </p>

                        @hasSection('auth-illustration')
                            <div class="mx-auto mt-5 flex max-w-[280px] items-center justify-center sm:max-w-xs lg:mt-6 lg:max-w-sm">
                                @yield('auth-illustration')
                            </div>
                        @endif
                    </div>

                    <div class="relative mt-5 hidden grid-cols-3 gap-3 text-center text-xs text-blue-100/75 sm:grid lg:mt-auto lg:pt-6">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">Acceso por rol</div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">Datos protegidos</div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">Trazabilidad</div>
                    </div>
                </section>

                <section class="p-6 sm:p-10 lg:p-12 xl:p-14">
                    <div class="mx-auto max-w-md">
                        @if ($errors->has('social'))
                            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                                {{ $errors->first('social') }}
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                                {{ session('status') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
