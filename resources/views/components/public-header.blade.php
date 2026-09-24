@props(['current' => 'home'])

<header class="sticky top-0 z-50 border-b border-white/10 bg-brand-900/95 text-white shadow-sm backdrop-blur-xl">
    <div class="mx-auto flex min-h-24 max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:gap-5 sm:px-6 lg:px-8">
        <a class="inline-flex min-w-0 items-center gap-3" href="{{ route('home') }}" aria-label="Ir al inicio">
            <img class="h-14 w-auto shrink-0 sm:h-16" src="{{ asset('img/LogoUNAQ.webp') }}" alt="Universidad Aeronáutica en Querétaro">
            <span class="hidden h-11 w-px bg-white/20 md:block"></span>
            <span class="hidden max-w-44 text-xs font-medium leading-5 text-blue-100 md:block">
                Plataforma Institucional<br>de Formación
            </span>
        </a>

        <nav class="hidden items-center gap-1 text-sm font-medium text-blue-100 lg:flex" aria-label="Navegación principal">
            <a class="rounded-lg px-4 py-2.5 transition hover:bg-white/10 hover:text-white" href="{{ route('home') }}">Inicio</a>
        </nav>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/20" type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18 15 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span class="hidden sm:inline">Cerrar sesión</span>
                    <span class="sm:hidden">Salir</span>
                </button>
            </form>
        @else
            <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-800 shadow-lg shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white/30 sm:px-5" href="{{ $current === 'login' ? route('home') : route('login') }}">
                @if ($current === 'login')
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                    <span class="hidden sm:inline">Volver al inicio</span><span class="sm:hidden">Inicio</span>
                @else
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/><path stroke-linecap="round" stroke-linejoin="round" d="m18 15 3-3m0 0-3-3m3 3H9"/></svg>
                    <span class="hidden sm:inline">Iniciar sesión</span><span class="sm:hidden">Acceder</span>
                @endif
            </a>
        @endauth
    </div>
</header>
