@extends('layouts.portal')

@section('content')
    <x-portal-page title="Cursos que imparto" description="Actividades en las que eres el profesor responsable.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($activities as $activity)
                <a class="group rounded-2xl border border-brand-100 bg-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl" href="{{ route('participant.professor.teaching.show', $activity) }}">
                    <div class="flex flex-col items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-portal-nav-icon name="teaching" /></span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-600">{{ $activity->activityType?->name ?? 'Actividad formativa' }}</p>
                            <h2 class="mt-2 text-lg font-extrabold text-slate-900">{{ $activity->name }}</h2>
                        </div>
                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ ucfirst(str_replace('_', ' ', $activity->status)) }}</span>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm">
                        <div class="rounded-xl bg-slate-50 p-3"><span class="block text-xs text-slate-400">Participantes</span><strong class="mt-1 block text-lg text-slate-800">{{ $activity->enrollments_count }}</strong></div>
                        <div class="rounded-xl bg-slate-50 p-3"><span class="block text-xs text-slate-400">Evidencias</span><strong class="mt-1 block text-lg text-slate-800">{{ $activity->evidences_count }}</strong></div>
                    </div>
                </a>
            @empty
                <p class="text-slate-500">Todavía no tienes actividades asignadas como profesor.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
