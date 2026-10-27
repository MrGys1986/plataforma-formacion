@extends('layouts.portal')

@section('content')
    <x-portal-page title="Mi formación" description="Cursos en los que participas, avance y evidencias propias.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($enrollments as $enrollment)
                <a class="group flex min-h-56 flex-col items-center rounded-2xl border border-brand-100 bg-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-950/10" href="{{ route('participant.learning.show', $enrollment) }}">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><x-portal-nav-icon name="courses" /></span>
                    <p class="mt-4 text-lg font-extrabold tracking-tight text-slate-900">{{ $enrollment->activity?->participant_name }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $enrollment->activity?->activityType?->name }}</p>
                    <div class="mt-3 flex flex-wrap justify-center gap-2 text-xs">
                        <span class="rounded-full bg-brand-50 px-3 py-1 font-bold text-brand-700">{{ ucfirst($enrollment->status) }}</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-600">{{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}</span>
                    </div>
                    @if ($enrollment->final_score !== null)
                        <p class="mt-3 text-sm text-slate-600">Calificación: {{ $enrollment->final_score }}</p>
                    @endif
                    <span class="mt-auto pt-5 text-sm font-bold text-brand-700">{{ $enrollment->status !== 'aprobada' ? 'Ver solicitud' : ($enrollment->completion_status === 'completado' ? 'Ver formación completada' : 'Continuar curso') }} →</span>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-brand-50/60 px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-700 shadow-sm"><x-portal-nav-icon name="courses" /></span>
                    <p class="mt-4 font-semibold text-slate-700">Aún no tienes inscripciones.</p>
                    <a class="mt-3 inline-flex rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-700" href="{{ route('participant.catalog.index') }}">Explorar cursos</a>
                </div>
            @endforelse
        </div>
        {{ $enrollments->links() }}
    </x-portal-page>
@endsection
