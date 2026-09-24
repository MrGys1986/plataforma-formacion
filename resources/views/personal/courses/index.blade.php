@extends('layouts.portal')

@section('content')
    <x-portal-page title="Mis actividades">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($activities as $activity)
                <a class="group flex min-h-44 flex-col items-center justify-center rounded-2xl border border-brand-100 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl" href="{{ route('personal.courses.show', $activity) }}">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-portal-nav-icon name="courses" /></span>
                    <span class="mt-4 font-extrabold text-slate-900">{{ $activity->name }}</span>
                    <span class="mt-3 text-sm font-bold text-brand-700">Abrir actividad →</span>
                </a>
            @empty
                <p class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-brand-50/60 px-6 py-12 text-center text-slate-600">No tienes actividades asignadas.</p>
            @endforelse
        </div>
        {{ $activities->links() }}
    </x-portal-page>
@endsection
