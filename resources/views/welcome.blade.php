<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma Institucional de Formación</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white antialiased">

<div class="relative min-h-screen overflow-hidden">

    {{-- Fondo decorativo --}}
    <div class="absolute inset-0 -z-10">
        <div class="absolute left-1/2 top-0 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-[400px] w-[400px] rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.16),transparent_35%),linear-gradient(to_bottom,rgba(15,23,42,0.2),rgba(2,6,23,1))]"></div>
    </div>

    {{-- Header --}}
    <header class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8 lg:py-4">
        <a href="/" class="inline-flex items-center">
            <img
                src="{{ asset('img/LogoUNAQ.webp') }}"
                alt="Logo de la universidad"
                class="h-14 w-auto rounded-md sm:h-20"
            >
        </a>

        <a
            href="{{ route('login') }}"
            class="group relative inline-flex shrink-0 items-center gap-2 overflow-hidden rounded-full bg-gradient-to-r from-cyan-300 via-blue-300 to-indigo-300 px-3.5 py-2.5 text-sm font-bold text-slate-950 shadow-[0_0_32px_rgba(56,189,248,0.5)] ring-4 ring-blue-400/15 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_0_42px_rgba(56,189,248,0.7)] focus:outline-none focus:ring-4 focus:ring-cyan-200/60 sm:gap-3 sm:px-5 sm:py-3"
        >
            <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/60 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
            <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-slate-950 text-white shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18 15 3-3m0 0-3-3m3 3H9"/>
                </svg>
            </span>
            <span class="relative sm:hidden">Acceder</span>
            <span class="relative hidden sm:inline">Iniciar sesión / Registro</span>
            <svg class="relative hidden h-4 w-4 transition-transform group-hover:translate-x-1 sm:block" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>
        </a>
    </header>

    <main class="mx-auto max-w-7xl px-6 pb-20 pt-0 lg:px-8 lg:pt-2">

        {{-- Hero --}}
        <section class="grid items-center gap-12 lg:grid-cols-2">

            <div>
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-400/20 bg-blue-400/10 px-4 py-2 text-sm font-medium text-blue-200">
                    <span class="h-2 w-2 rounded-full bg-blue-300"></span>
                    Gestión académica, evidencias y microcredenciales
                </div>

                <h1 class="max-w-3xl text-4xl font-bold leading-tight tracking-tight text-white md:text-6xl">
                    Formación, evidencias y microcredenciales en una sola plataforma
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                    Centraliza la administración de actividades académicas, rutas de aprendizaje,
                    inscripciones, evaluaciones, constancias y trazabilidad institucional en un entorno
                    moderno, seguro y fácil de utilizar.
                </p>

                <div class="mt-10 grid max-w-xl grid-cols-1 gap-4 text-center sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <p class="text-2xl font-bold text-white">100%</p>
                        <p class="mt-1 text-sm text-slate-400">Trazabilidad</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <p class="text-2xl font-bold text-white">24/7</p>
                        <p class="mt-1 text-sm text-slate-400">Consulta digital</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <p class="text-2xl font-bold text-white">Multirol</p>
                        <p class="mt-1 text-sm text-slate-400">Usuarios y áreas</p>
                    </div>
                </div>
            </div>

            {{-- Tarjeta visual derecha --}}
            <div class="relative">
                <div class="absolute -inset-4 rounded-[2rem] bg-blue-500/20 blur-2xl"></div>

                <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 p-5 shadow-2xl backdrop-blur-xl">
                    <div class="rounded-[1.5rem] border border-white/10 bg-slate-900/90 p-5">

                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <div>
                                <p class="text-sm text-slate-400">Panel institucional</p>
                                <p class="mt-1 text-lg font-semibold text-white">Resumen general</p>
                            </div>

                            <div class="rounded-full bg-emerald-400/10 px-3 py-1 text-sm font-medium text-emerald-300">
                                Activo
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">

                            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/15 text-blue-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75v10.5m5.25-5.25H6.75"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15A1.5 1.5 0 0 0 21 18V6a1.5 1.5 0 0 0-1.5-1.5h-15A1.5 1.5 0 0 0 3 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400">Actividades</p>
                                <p class="mt-1 text-2xl font-bold text-white">128</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/15 text-cyan-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400">Evidencias</p>
                                <p class="mt-1 text-2xl font-bold text-white">342</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9a3 3 0 0 1-3-3v-7.5a3 3 0 0 1 3-3h9a3 3 0 0 1 3 3v7.5a3 3 0 0 1-3 3Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.75h6M9 13.5h3"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400">Constancias</p>
                                <p class="mt-1 text-2xl font-bold text-white">89</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/15 text-violet-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 14.25 9l5.25.45-4 3.45 1.2 5.1L12 15.3 7.3 18l1.2-5.1-4-3.45L9.75 9 12 3.75Z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400">Microcredenciales</p>
                                <p class="mt-1 text-2xl font-bold text-white">56</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl border border-white/10 bg-gradient-to-r from-blue-500/20 to-cyan-500/10 p-5 text-center">
                            <p class="font-semibold text-white">Seguimiento institucional</p>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                Consulta el avance de participantes, actividades, evidencias cargadas,
                                evaluaciones y emisión de constancias desde un solo espacio.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        {{-- Beneficios --}}
        <section class="mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-300">
                    Beneficios
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-white md:text-4xl">
                    Una plataforma pensada para simplificar la gestión académica
                </h2>

                <p class="mt-4 text-slate-300">
                    El sistema permite ordenar procesos, reducir trabajo manual y mantener evidencia clara
                    del avance académico y formativo dentro de la institución.
                </p>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                <article class="rounded-2xl border border-white/10 bg-white/[0.04] p-6 text-center transition hover:-translate-y-1 hover:bg-white/[0.07]">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/15 text-blue-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M4.5 12h15m-15 5.25h9"/>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-white">Control centralizado</h3>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Administra actividades, usuarios, inscripciones, evidencias y constancias desde un mismo panel.
                    </p>
                </article>

                <article class="rounded-2xl border border-white/10 bg-white/[0.04] p-6 text-center transition hover:-translate-y-1 hover:bg-white/[0.07]">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/15 text-cyan-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-white">Trazabilidad completa</h3>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Da seguimiento al historial de participación, carga de archivos, validaciones y resultados.
                    </p>
                </article>

                <article class="rounded-2xl border border-white/10 bg-white/[0.04] p-6 text-center transition hover:-translate-y-1 hover:bg-white/[0.07]">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 19.5 7.5v9L12 20.25 4.5 16.5v-9L12 3.75Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12 19.5 7.5M12 12 4.5 7.5M12 12v8.25"/>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-white">Microcredenciales</h3>

                    <p class="mt-3 text-sm leading-6 text-slate-400">
                        Facilita la emisión, consulta y validación de constancias o reconocimientos digitales.
                    </p>
                </article>
            </div>
        </section>

        {{-- Funciones --}}
        <section class="mt-24 rounded-[2rem] border border-white/10 bg-white/[0.04] p-6 md:p-10">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-300">
                    Funciones principales
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-white">
                    Herramientas para cada etapa del proceso
                </h2>

                <p class="mt-4 text-slate-300">
                    La plataforma está diseñada para acompañar el ciclo completo de formación:
                    planeación, inscripción, seguimiento, evaluación y emisión de evidencias.
                </p>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-slate-950/60 p-5 text-center">
                    <h3 class="font-semibold text-white">Gestión de actividades</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Registro de cursos, talleres, capacitaciones, rutas formativas y eventos académicos.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-950/60 p-5 text-center">
                    <h3 class="font-semibold text-white">Inscripciones</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Control de participantes, cupos, estados de inscripción y validaciones internas.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-950/60 p-5 text-center">
                    <h3 class="font-semibold text-white">Carga de evidencias</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Recepción de archivos, revisión documental y seguimiento por usuario o actividad.
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-950/60 p-5 text-center">
                    <h3 class="font-semibold text-white">Evaluación y constancias</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-400">
                        Registro de resultados, generación de constancias y consulta de historial académico.
                    </p>
                </div>
            </div>
        </section>

    </main>

    <footer class="border-t border-white/10 px-6 py-6">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-center gap-2 text-center text-sm text-slate-400">
            <p>© {{ date('Y') }} Plataforma Institucional de Formación.</p>
            <p>Gestión académica digital</p>
        </div>
    </footer>

</div>

</body>
</html>
