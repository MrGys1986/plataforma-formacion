<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plataforma Institucional de Formación y Microcredenciales de la Universidad Aeronáutica en Querétaro.">
    <title>Plataforma Institucional de Formación y Microcredenciales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
    <div class="flex min-h-screen flex-col">
        <x-public-header />

        <main class="flex-1">
            <section class="relative isolate overflow-hidden bg-white">
                <div class="pointer-events-none absolute inset-0 -z-10">
                    <div class="absolute -right-40 -top-52 h-[36rem] w-[36rem] rounded-full bg-brand-100/80 blur-3xl"></div>
                    <div class="absolute -left-48 bottom-0 h-80 w-80 rounded-full bg-blue-50 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0d55950a_1px,transparent_1px),linear-gradient(to_bottom,#0d55950a_1px,transparent_1px)] bg-[size:36px_36px] [mask-image:linear-gradient(to_bottom,black,transparent_85%)]"></div>
                </div>

                <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-24 xl:gap-20">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3.5 py-2 text-xs font-bold uppercase tracking-[0.14em] text-brand-800 sm:text-sm">
                            <span class="h-2 w-2 rounded-full bg-brand-500 ring-4 ring-brand-100"></span>
                            Tu espacio para aprender y crecer
                        </div>

                        <h1 class="mt-6 text-4xl font-extrabold leading-[1.08] tracking-tight text-brand-950 sm:text-5xl lg:text-6xl">
                            Aprende, avanza y alcanza
                            <span class="text-brand-600">nuevas metas.</span>
                        </h1>

                        <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                            Descubre oportunidades de formación, continúa tu aprendizaje y consulta tus logros desde un espacio sencillo, ordenado y accesible.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-brand-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-brand-700/20 transition hover:-translate-y-0.5 hover:bg-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-200" href="{{ route('login') }}">
                                Acceder a la plataforma
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                            </a>
                            <a class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-100" href="#beneficios">Conocer beneficios</a>
                        </div>

                        <div class="mt-9 grid max-w-xl gap-3 border-t border-slate-200 pt-6 sm:grid-cols-3">
                            @foreach (['Fácil de utilizar', 'Acceso seguro', 'Tu avance contigo'] as $feature)
                                <div class="flex items-center justify-center gap-2 text-center text-sm font-semibold text-slate-600 sm:justify-start sm:text-left">
                                    <svg class="h-5 w-5 shrink-0 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    {{ $feature }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative mx-auto flex w-full max-w-2xl items-center justify-center lg:justify-end">
                        <div class="absolute left-[8%] top-[10%] -z-10 h-40 w-40 rounded-full bg-brand-100/80 blur-2xl sm:h-56 sm:w-56"></div>
                        <div class="absolute bottom-[5%] right-[2%] -z-10 h-32 w-32 rounded-full bg-blue-100/70 blur-2xl sm:h-44 sm:w-44"></div>
                        <img
                            class="h-auto w-full drop-shadow-[0_24px_28px_rgba(7,38,64,0.14)]"
                            src="{{ asset('img/online-learning.svg') }}"
                            alt="Ilustración de aprendizaje en línea"
                            width="692"
                            height="500"
                        >
                    </div>
                </div>
            </section>

            <section id="beneficios" class="scroll-mt-24 border-y border-slate-200 bg-brand-50/70 py-16 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-brand-700">Beneficios</p>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-brand-950 sm:text-4xl">Todo lo que necesitas para seguir creciendo.</h2>
                        <p class="mt-4 text-base leading-7 text-slate-600">Una experiencia pensada para ayudarte a encontrar oportunidades, continuar aprendiendo y reconocer cada logro.</p>
                    </div>

                    <div class="mt-10 grid gap-5 sm:mt-12 md:grid-cols-3">
                        @foreach ([
                            ['01', 'Aprende a tu ritmo', 'Entra cuando lo necesites y continúa desde el punto en el que te quedaste.'],
                            ['02', 'Todo en un solo lugar', 'Encuentra tus opciones de formación, actividades y avances de manera sencilla.'],
                            ['03', 'Reconoce tus logros', 'Consulta los resultados y reconocimientos obtenidos durante tu formación.'],
                        ] as $index => $benefit)
                            <article class="group relative flex min-h-72 flex-col items-center overflow-hidden rounded-3xl border border-brand-100 bg-white px-6 py-8 text-center shadow-[0_16px_40px_rgba(7,38,64,0.07)] transition duration-300 hover:-translate-y-1.5 hover:border-brand-300 hover:shadow-[0_22px_50px_rgba(7,38,64,0.13)] sm:px-7">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-400 via-brand-600 to-brand-400 opacity-80"></div>
                                <span class="absolute right-5 top-5 text-xs font-extrabold tracking-[0.16em] text-brand-200">{{ $benefit[0] }}</span>
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-700 to-brand-500 text-white shadow-lg shadow-brand-700/20 transition duration-300 group-hover:scale-105">
                                    @if ($index === 0)
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h10.5"/></svg>
                                    @elseif ($index === 1)
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V9.75m5 9.75V4.5m5 15v-6m5 6V7.75"/></svg>
                                    @else
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.4 4.8 5.35.78-3.88 3.77.92 5.33L12 15.17l-4.79 2.51.92-5.33-3.88-3.77 5.35-.78L12 3Z"/></svg>
                                    @endif
                                </div>
                                <h3 class="mt-6 text-xl font-extrabold text-brand-950">{{ $benefit[1] }}</h3>
                                <p class="mt-3 max-w-xs text-sm leading-6 text-slate-600">{{ $benefit[2] }}</p>
                                <span class="mt-auto block h-1 w-10 rounded-full bg-brand-200 transition-all duration-300 group-hover:w-16 group-hover:bg-brand-500"></span>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="funciones" class="scroll-mt-24 bg-white py-16 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-brand-700">Conoce la plataforma</p>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-brand-950 sm:text-4xl">Un espacio que te acompaña en cada paso.</h2>
                        <p class="mt-5 text-base leading-7 text-slate-600">Encuentra de forma rápida lo más importante para disfrutar una experiencia de aprendizaje clara y sin complicaciones.</p>
                    </div>

                    <div class="mt-10 grid gap-5 sm:grid-cols-2 sm:mt-12 lg:grid-cols-4">
                        @foreach ([
                            ['Explora oportunidades', 'Conoce las opciones de formación disponibles para ti.'],
                            ['Avanza paso a paso', 'Continúa aprendiendo de manera clara y organizada.'],
                            ['Mantente al día', 'Consulta fácilmente tus actividades y próximos pasos.'],
                            ['Consulta tus logros', 'Revisa los reconocimientos obtenidos en tu camino.'],
                        ] as $index => $item)
                            <article class="group flex min-h-64 flex-col items-center justify-center rounded-3xl border border-slate-200 bg-gradient-to-b from-white to-brand-50/60 p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:bg-brand-50 hover:shadow-xl hover:shadow-brand-950/10">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-brand-100 bg-white text-brand-700 shadow-md shadow-brand-950/5 transition duration-300 group-hover:border-brand-600 group-hover:bg-brand-700 group-hover:text-white">
                                    @if ($index === 0)
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h15v13.5h-15V5.25Zm3 3h9m-9 3.75h9m-9 3.75H12"/></svg>
                                    @elseif ($index === 1)
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 12a7.5 7.5 0 0 0-15 0"/></svg>
                                    @elseif ($index === 2)
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h7.5l3 3v13.5H6.75V3.75Zm3 7.5 1.5 1.5 3-3m-4.5 6h4.5"/></svg>
                                    @else
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5h12v15H6v-15Zm3 3h6m-6 3h6m-6 3h3"/></svg>
                                    @endif
                                </div>
                                <h3 class="mt-5 text-lg font-extrabold text-brand-950">{{ $item[0] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item[1] }}</p>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-9 text-center">
                        <a class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-brand-200 bg-brand-50 px-6 py-3 text-sm font-bold text-brand-800 transition hover:border-brand-400 hover:bg-brand-100 focus:outline-none focus:ring-4 focus:ring-brand-100" href="{{ route('login') }}">Comenzar ahora <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </section>

            <section class="bg-brand-700 px-4 py-12 sm:px-6 sm:py-14 lg:px-8">
                <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-blue-200">Comienza ahora</p>
                        <h2 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Tu próxima experiencia comienza aquí.</h2>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Ingresa y descubre todo lo que la plataforma tiene para ti.</p>
                    </div>
                    <a class="inline-flex min-h-12 w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-brand-800 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/30 sm:w-auto" href="{{ route('login') }}">Iniciar sesión <span aria-hidden="true">→</span></a>
                </div>
            </section>
        </main>

        <x-public-footer />
    </div>
</body>
</html>
