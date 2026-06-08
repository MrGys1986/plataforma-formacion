@extends('layouts.public')

@section('title', 'Acceso institucional')

@section('content')
    <x-portal-page title="Acceso institucional" description="Ingresa con tu cuenta registrada en la plataforma.">
        <form class="mx-auto max-w-md space-y-4" method="POST" action="{{ route('login.store') }}">
            @csrf

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Correo electrónico</span>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium">Contraseña</span>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2" type="password" name="password" required>
            </label>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1">
                Mantener la sesión iniciada
            </label>

            @error('email')
                <p class="text-sm text-red-700">{{ $message }}</p>
            @enderror

            <button class="w-full rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white" type="submit">
                Ingresar
            </button>
        </form>
    </x-portal-page>
@endsection
