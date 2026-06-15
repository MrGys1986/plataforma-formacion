@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('auth-illustration')
    <img
        class="h-44 w-full object-contain sm:h-52 lg:h-56 xl:h-60"
        src="{{ asset('img/login.svg') }}"
        alt="Ilustración de acceso seguro a la plataforma"
    >
@endsection

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Bienvenido</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Inicia sesión</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            Usa las credenciales asignadas por la institución o continúa con tu proveedor autorizado.
        </p>
    </div>

    <form class="mt-8 space-y-5" method="POST" action="{{ route('login.store') }}">
        @csrf

        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Correo electrónico</span>
            <input
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nombre@correo.com"
                maxlength="255"
                autocomplete="email"
                required
                autofocus
            >
            @error('email')
                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
            @enderror
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Contraseña</span>
            <input
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                type="password"
                name="password"
                placeholder="Tu contraseña"
                maxlength="255"
                autocomplete="current-password"
                required
            >
        </label>

        <label class="flex items-center gap-3 text-sm text-slate-600">
            <input class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-600" type="checkbox" name="remember" value="1">
            Mantener la sesión iniciada
        </label>

        <button class="w-full rounded-xl bg-slate-950 px-4 py-3 font-semibold text-white shadow-lg shadow-slate-950/15 transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200" type="submit">
            Ingresar
        </button>
    </form>

    <div class="my-7 flex items-center gap-4">
        <div class="h-px flex-1 bg-slate-200"></div>
        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">o inicia sesión con</span>
        <div class="h-px flex-1 bg-slate-200"></div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        <a class="inline-flex items-center justify-center gap-3 rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50" href="{{ route('oauth.microsoft.redirect') }}">
            <svg class="h-5 w-5" viewBox="0 0 21 21" aria-hidden="true">
                <path fill="#f25022" d="M1 1h9v9H1z"/>
                <path fill="#7fba00" d="M11 1h9v9h-9z"/>
                <path fill="#00a4ef" d="M1 11h9v9H1z"/>
                <path fill="#ffb900" d="M11 11h9v9h-9z"/>
            </svg>
            Microsoft
        </a>

        <a class="inline-flex items-center justify-center gap-3 rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50" href="{{ route('oauth.google.redirect') }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.06H12v3.9h5.38a4.6 4.6 0 0 1-2 3.02v2.53h3.24c1.9-1.75 2.98-4.33 2.98-7.39Z"/>
                <path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.62-2.38l-3.24-2.53c-.9.6-2.05.96-3.38.96-2.6 0-4.8-1.76-5.6-4.12H3.05v2.6A10 10 0 0 0 12 22Z"/>
                <path fill="#FBBC05" d="M6.4 13.93a6 6 0 0 1 0-3.86v-2.6H3.05a10 10 0 0 0 0 9.06l3.35-2.6Z"/>
                <path fill="#EA4335" d="M12 5.95c1.47 0 2.8.5 3.84 1.5l2.87-2.88A9.64 9.64 0 0 0 12 2 10 10 0 0 0 3.05 7.47l3.35 2.6c.8-2.36 3-4.12 5.6-4.12Z"/>
            </svg>
            Google
        </a>
    </div>

    <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-5 text-center">
        <p class="font-semibold text-blue-950">¿Eres participante externo?</p>
        <p class="mt-1 text-sm leading-6 text-blue-800">
            El registro requiere una cuenta personal de Google terminada en <strong>@gmail.com</strong>.
        </p>
        <a class="mt-4 inline-flex rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-600" href="{{ route('register') }}">
            Registrarme como externo
        </a>
    </div>
@endsection
