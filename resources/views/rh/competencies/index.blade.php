@extends('layouts.rh')

@section('content')
<x-portal-page title="Cursos requeridos" description="Catálogo de cursos y minicursos que debe realizar el personal de Recursos Humanos.">
    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <p class="font-semibold text-blue-900">Formación para Recursos Humanos</p>
        <p class="mt-1 text-sm leading-6 text-blue-800">Consulta la capacitación necesaria para el puesto. Las actividades disponibles y el avance del personal se actualizan desde este catálogo.</p>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        @forelse($programs as $program)
        <article class="flex flex-col rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">{{ $program->activityType?->name ?? 'Curso' }}</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $program->name }}</h2>
                </div>
                <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Requerido</span>
            </div>

            @if($program->description)<p class="mt-3 text-sm leading-6 text-slate-600">{{ $program->description }}</p>@endif

            <dl class="mt-5 grid gap-4 rounded-lg bg-slate-50 p-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Modalidad</dt><dd class="mt-1 text-slate-800">{{ ucfirst($program->default_modality) }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Duración</dt><dd class="mt-1 text-slate-800">{{ (float) $program->duration_hours }} horas</dd></div>
                <div><dt class="font-semibold text-slate-500">Área</dt><dd class="mt-1 text-slate-800">{{ $program->area?->name ?? 'Formación transversal' }}</dd></div>
                <div><dt class="font-semibold text-slate-500">Constancia</dt><dd class="mt-1 text-slate-800">{{ $program->generates_certificate ? 'Sí' : 'No' }}</dd></div>
            </dl>

            <div class="mt-auto grid grid-cols-3 gap-3 border-t border-slate-100 pt-5 text-center">
                <div><p class="text-2xl font-bold text-slate-900">{{ $program->editions_count }}</p><p class="text-xs text-slate-500">Actividades</p></div>
                <div><p class="text-2xl font-bold text-blue-700">{{ $program->enrollments_count }}</p><p class="text-xs text-slate-500">Inscripciones</p></div>
                <div><p class="text-2xl font-bold text-emerald-700">{{ $program->completed_enrollments_count }}</p><p class="text-xs text-slate-500">Completados</p></div>
            </div>
        </article>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No hay cursos requeridos para Recursos Humanos.</div>@endforelse
    </div>
    @if($programs->hasPages())<div class="mt-6">{{ $programs->links() }}</div>@endif
</x-portal-page>
@endsection
