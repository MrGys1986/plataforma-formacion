@extends('layouts.participant')

@section('content')
    <x-portal-page title="Cursos que imparto" description="Ediciones en las que eres el profesor responsable.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($activities as $activity)
                <a class="rounded-xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md" href="{{ route('participant.professor.teaching.show', $activity) }}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-600">{{ $activity->edition_code ?: 'Edición '.$activity->edition_number }}</p>
                            <h2 class="mt-2 font-semibold text-slate-900">{{ $activity->name }}</h2>
                        </div>
                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ ucfirst(str_replace('_', ' ', $activity->status)) }}</span>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 text-sm">
                        <div><span class="block text-slate-400">Participantes</span><strong class="text-slate-800">{{ $activity->enrollments_count }}</strong></div>
                        <div><span class="block text-slate-400">Evidencias</span><strong class="text-slate-800">{{ $activity->evidences_count }}</strong></div>
                    </div>
                </a>
            @empty
                <p class="text-slate-500">Todavía no tienes ediciones asignadas como profesor.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $activities->links() }}</div>
    </x-portal-page>
@endsection
