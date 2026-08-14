@extends('layouts.participant')

@section('content')
    <x-portal-page title="Perfil docente" description="Administra tu información y consulta tus reconocimientos.">
        <nav class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3" aria-label="Opciones del perfil">
            <a class="flex items-center justify-between rounded-xl border border-violet-200 bg-violet-50 px-5 py-4 font-semibold text-violet-700 transition hover:border-violet-300 hover:bg-violet-100" href="{{ route('participant.badges.index') }}">
                <span>Mis insignias</span>
                <span aria-hidden="true">→</span>
            </a>
            <a class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100" href="{{ route('participant.certificates.index') }}">
                <span>Mis constancias</span>
                <span aria-hidden="true">→</span>
            </a>
            <a class="flex items-center justify-between rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-100" href="#fotografia-perfil">
                <span>Cambiar fotografía de perfil</span>
                <span aria-hidden="true">↓</span>
            </a>
        </nav>

        <section id="fotografia-perfil" class="flex scroll-mt-6 flex-col gap-5 rounded-xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center">
            @if(auth()->user()->avatarFile)
                <img class="h-24 w-24 rounded-full object-cover ring-4 ring-white" src="{{ auth()->user()->avatarFile->optimizedImageUrl(240, 240) }}" alt="Fotografía de {{ auth()->user()->name }}">
            @else
                <span class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 text-3xl font-bold text-blue-700">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            @endif
            <div class="flex-1"><h2 class="font-semibold text-slate-900">Fotografía de perfil</h2><p class="mt-1 text-sm text-slate-500">JPG, PNG o WebP. Cloudinary genera automáticamente los tamaños optimizados.</p><form class="mt-3 flex flex-col gap-3 sm:flex-row" method="POST" action="{{ route('participant.professor.profile.avatar') }}" enctype="multipart/form-data">@csrf<input class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-4 file:py-2 file:font-semibold file:text-blue-700" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white" type="submit">Actualizar foto</button></form>@error('avatar')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror</div>
        </section>
    </x-portal-page>
@endsection
