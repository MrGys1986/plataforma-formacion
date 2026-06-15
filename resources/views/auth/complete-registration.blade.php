@extends('layouts.auth')

@section('title', 'Completar registro externo')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">Google verificado</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Completa tu perfil</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            Tu correo personal fue validado. Agrega los datos necesarios para participar en los cursos.
        </p>
    </div>

    <div class="mt-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Correo validado</p>
            <p class="font-medium text-emerald-950">{{ $pendingRegistration['email'] }}</p>
        </div>
    </div>

    <form class="mt-7 space-y-5" method="POST" action="{{ route('register.store') }}">
        @csrf

        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Nombre completo</span>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100" type="text" name="name" value="{{ old('name', $pendingRegistration['name']) }}" maxlength="255" autocomplete="name" required autofocus>
            @error('name')
                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
            @enderror
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Institución, empresa o procedencia</span>
            <input class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100" type="text" name="external_institution" value="{{ old('external_institution') }}" maxlength="255" required>
            @error('external_institution')
                <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
            @enderror
        </label>

        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Teléfono <span class="font-normal text-slate-400">(opcional)</span></span>
                <input class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel">
                @error('phone')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">CURP <span class="font-normal text-slate-400">(opcional)</span></span>
                <input class="w-full rounded-xl border border-slate-300 px-4 py-3 uppercase outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100" type="text" name="curp" value="{{ old('curp') }}" minlength="18" maxlength="18" autocomplete="off">
                @error('curp')
                    <span class="mt-2 block text-sm text-red-700">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <button class="w-full rounded-xl bg-blue-700 px-4 py-3 font-semibold text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-200" type="submit">
            Finalizar registro
        </button>
    </form>
@endsection
