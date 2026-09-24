<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Acceso seguro a la Plataforma Institucional de Formación y Microcredenciales.">
    <title>@yield('title', 'Acceso') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-brand-50 text-slate-900 antialiased">
    <div class="flex min-h-screen flex-col">
        <x-public-header current="login" />

        <main class="relative isolate flex flex-1 items-center overflow-hidden px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
            <div class="pointer-events-none absolute inset-0 -z-10">
                <div class="absolute -left-40 top-16 h-96 w-96 rounded-full bg-white blur-3xl"></div>
                <div class="absolute -right-40 bottom-0 h-[30rem] w-[30rem] rounded-full bg-brand-200/55 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#0d559508_1px,transparent_1px),linear-gradient(to_bottom,#0d559508_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            </div>

            <div class="mx-auto grid w-full max-w-6xl overflow-hidden rounded-[1.75rem] border border-brand-100 bg-white shadow-2xl shadow-brand-950/15 lg:grid-cols-[1.02fr_0.98fr]">
                <section class="relative isolate overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 px-6 py-9 text-white sm:px-10 sm:py-11 lg:flex lg:min-h-[650px] lg:flex-col lg:justify-between lg:p-12">
                    <div class="pointer-events-none absolute inset-0 -z-10">
                        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full border-[44px] border-white/5"></div>
                        <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-brand-400/20 blur-3xl"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(255,255,255,0.13),transparent_27%)]"></div>
                    </div>

                    <div class="max-w-lg">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 text-xs font-bold uppercase tracking-[0.16em] text-blue-100 backdrop-blur-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-200"></span>
                            Acceso institucional seguro
                        </span>
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Tu aprendizaje y tus logros en un solo espacio.</h1>
                        <p class="mt-4 max-w-md text-sm leading-7 text-blue-100/80 sm:text-base">Ingresa para descubrir oportunidades y continuar avanzando en tu formación.</p>
                    </div>

                    @hasSection('auth-illustration')
                        <div class="mx-auto mt-7 hidden w-full max-w-sm items-center justify-center sm:flex lg:my-6">
                            @yield('auth-illustration')
                        </div>
                    @endif

                    <div class="mt-8 hidden grid-cols-3 gap-3 border-t border-white/15 pt-6 text-center text-xs font-semibold text-blue-100/75 sm:grid lg:mt-0">
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Fácil de usar</div>
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Acceso seguro</div>
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Siempre disponible</div>
                    </div>
                </section>

                <section class="flex items-center px-6 py-9 sm:px-10 sm:py-11 lg:px-12 xl:px-16">
                    <div class="mx-auto w-full max-w-md">
                        @if ($errors->has('social'))
                            <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                {{ $errors->first('social') }}
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="mb-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                {{ session('status') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </section>
            </div>
        </main>

        <x-public-footer />
    </div>
</body>
</html>
