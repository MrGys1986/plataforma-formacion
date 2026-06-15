@extends('layouts.auth')

@section('title', 'Registro externo')

@section('auth-illustration')
    <img
        class="h-44 w-full object-contain sm:h-52 lg:h-56 xl:h-60"
        src="{{ asset('img/register.svg') }}"
        alt="Ilustración del registro de participante externo"
    >
@endsection

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Participantes externos</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Crea tu cuenta con Google</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            Para proteger tu identidad, el registro externo se realiza exclusivamente mediante una cuenta personal de Google.
        </p>
    </div>

    <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-950">
        <p class="font-semibold">Requisito de registro</p>
        <p class="mt-1">
            El correo debe terminar en <strong>@gmail.com</strong>. No se aceptan correos institucionales, empresariales ni cuentas de Google Workspace.
        </p>
    </div>

    <a class="mt-6 inline-flex w-full items-center justify-center gap-3 rounded-xl bg-white px-4 py-3.5 font-semibold text-slate-800 shadow-sm ring-1 ring-slate-300 transition hover:bg-blue-50 hover:ring-blue-300 focus:outline-none focus:ring-4 focus:ring-blue-100" href="{{ route('register.google') }}">
        <svg class="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#4285F4" d="M21.6 12.23c0-.71-.06-1.4-.18-2.06H12v3.9h5.38a4.6 4.6 0 0 1-2 3.02v2.53h3.24c1.9-1.75 2.98-4.33 2.98-7.39Z"/>
            <path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.62-2.38l-3.24-2.53c-.9.6-2.05.96-3.38.96-2.6 0-4.8-1.76-5.6-4.12H3.05v2.6A10 10 0 0 0 12 22Z"/>
            <path fill="#FBBC05" d="M6.4 13.93a6 6 0 0 1 0-3.86v-2.6H3.05a10 10 0 0 0 0 9.06l3.35-2.6Z"/>
            <path fill="#EA4335" d="M12 5.95c1.47 0 2.8.5 3.84 1.5l2.87-2.88A9.64 9.64 0 0 0 12 2 10 10 0 0 0 3.05 7.47l3.35 2.6c.8-2.36 3-4.12 5.6-4.12Z"/>
        </svg>
        Continuar con Google
    </a>

    <div class="mt-6 space-y-3 rounded-2xl bg-slate-50 p-5 text-sm text-slate-600">
        <div class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">1</span>
            <p>Google valida que el correo sea personal y esté verificado.</p>
        </div>
        <div class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">2</span>
            <p>Completarás únicamente tus datos de procedencia y contacto.</p>
        </div>
        <div class="flex gap-3">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">3</span>
            <p>La plataforma asignará automáticamente el perfil seguro de participante externo.</p>
        </div>
    </div>

    <p class="mt-8 text-center text-sm text-slate-600">
        ¿Ya tienes una cuenta?
        <a class="font-semibold text-blue-700 hover:text-blue-600" href="{{ route('login') }}">Iniciar sesión</a>
    </p>
@endsection
