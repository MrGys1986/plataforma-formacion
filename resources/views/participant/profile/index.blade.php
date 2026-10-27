@extends('layouts.portal')

@section('content')
    <div class="space-y-7">
        <header class="relative overflow-hidden rounded-3xl bg-brand-950 shadow-xl shadow-brand-950/10">
            <div class="pointer-events-none absolute -right-12 -top-24 h-80 w-80 rounded-full border-[36px] border-white/5" aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 right-0 h-44 w-96 bg-gradient-to-tl from-brand-500/25 to-transparent" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-7 p-6 sm:p-8 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    @if (auth()->user()->avatarFile)
                        <img class="h-24 w-24 shrink-0 rounded-2xl object-cover ring-4 ring-white/15" src="{{ auth()->user()->avatarFile->optimizedImageUrl(240, 240) }}" alt="Fotografía de {{ auth()->user()->name }}">
                    @else
                        <span class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl bg-brand-700 text-4xl font-bold text-white ring-4 ring-white/10" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-200">Mi perfil</p>
                        <h1 class="mt-2 break-words text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ auth()->user()->name }}</h1>
                        <p class="mt-1 break-all text-sm text-brand-100">{{ auth()->user()->email }}</p>
                        <p class="mt-4 max-w-lg text-sm leading-6 text-brand-100/85">Tu formación y tus logros, reunidos en un solo lugar. Sigue aprendiendo a tu ritmo.</p>
                    </div>
                </div>
                <nav class="flex flex-wrap gap-3 xl:shrink-0" aria-label="Accesos del perfil">
                    <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-900 shadow-sm hover:bg-brand-50 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white" href="{{ route('participant.catalog.index') }}">
                        <x-portal-nav-icon name="catalog" />
                        Explorar formación
                    </a>
                    <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/25 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white" href="{{ route('participant.badges.index') }}">
                        <x-portal-nav-icon name="certificate" />
                        Mis insignias
                    </a>
                </nav>
            </div>
        </header>

        <dl class="grid gap-3 sm:grid-cols-3 sm:gap-5">
            <div class="flex items-center gap-4 rounded-2xl border border-brand-100 bg-white p-5 shadow-sm">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700"><x-portal-nav-icon name="courses" /></span>
                <div><dt class="text-sm font-medium text-slate-500">Formaciones completadas</dt><dd class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ $completedEnrollments->total() }}</dd></div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-brand-100 bg-white p-5 shadow-sm">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" /></svg>
                </span>
                <div><dt class="text-sm font-medium text-slate-500">Horas de formación</dt><dd class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ number_format($completedHours, $completedHours == floor($completedHours) ? 0 : 1) }}</dd></div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-brand-100 bg-white p-5 shadow-sm">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><x-portal-nav-icon name="certificate" /></span>
                <div><dt class="text-sm font-medium text-slate-500">Constancias disponibles</dt><dd class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ $availableCertificates }}</dd></div>
            </div>
        </dl>

        <section aria-labelledby="completed-training-heading">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 id="completed-training-heading" class="text-xl font-extrabold tracking-tight text-slate-900">Mi formación completada</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Cursos, minicursos y talleres que ya forman parte de tu trayectoria.</p>
                </div>
                <a class="inline-flex min-h-11 items-center gap-2 text-sm font-bold text-brand-700 hover:text-brand-900" href="{{ route('participant.my-courses.index') }}">Ver toda mi formación <span aria-hidden="true">→</span></a>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($completedEnrollments as $enrollment)
                    @php
                        $activity = $enrollment->activity;
                        $name = $activity?->participant_name ?? 'Formación completada';
                        $coverFile = $activity?->coverFile ?? $activity?->trainingProgram?->coverFile;
                        $courseName = Illuminate\Support\Str::lower($name);
                        $fallbackCover = match (true) {
                            str_contains($courseName, 'liderazgo') => 'course-leadership.png',
                            str_contains($courseName, 'protección'), str_contains($courseName, 'datos personales') => 'course-data-protection.png',
                            str_contains($courseName, 'diseño de experiencias') => 'course-learning-design.png',
                            str_contains($courseName, 'evaluación') => 'course-assessment.png',
                            str_contains($courseName, 'herramientas digitales') => 'course-digital-tools.png',
                            str_contains($courseName, 'inducción') => 'course-induction.png',
                            default => 'course-general.png',
                        };
                        $certificates = $enrollment->certificates->filter(fn ($certificate) => $certificate->fileUpload);
                    @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-950/10">
                        <a class="relative block aspect-video overflow-hidden bg-brand-50 focus-visible:outline-2 focus-visible:outline-brand-600" href="{{ route('participant.learning.show', $enrollment) }}" aria-label="Ver {{ $name }}">
                            <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transform-none" src="{{ $coverFile?->optimizedImageUrl(960, 540) ?? asset('img/courses/'.$fallbackCover) }}" alt="Portada de {{ $name }}" loading="lazy" decoding="async">
                            <span class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-950/70 to-transparent" aria-hidden="true"></span>
                            <span class="absolute bottom-3 left-4 text-xs font-bold uppercase tracking-wider text-white">{{ $activity?->activityType?->name ?? 'Formación' }}</span>
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 shadow-sm">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                                Completado
                            </span>
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-lg font-extrabold leading-snug tracking-tight text-slate-900">{{ $name }}</h3>
                            <div class="mt-5 flex items-center justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3">
                                <div><p class="text-xs font-medium text-slate-500">Calificación</p><p class="mt-1 text-xl font-extrabold tabular-nums text-brand-800">{{ $enrollment->final_score ?? 'Pendiente' }}</p></div>
                                <div class="text-right"><p class="text-xs font-medium text-slate-500">Duración</p><p class="mt-1 text-sm font-bold text-slate-700">{{ $activity?->duration_hours ?? '—' }} horas</p></div>
                            </div>
                            <div class="mt-auto space-y-3 pt-5">
                                @forelse ($certificates as $certificate)
                                    <a class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600" href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 16v4h14v-4" /></svg>
                                        Descargar constancia
                                    </a>
                                @empty
                                    <p class="rounded-xl border border-dashed border-slate-200 px-3 py-3 text-center text-xs font-medium text-slate-500">Constancia pendiente de emisión.</p>
                                @endforelse
                                <a class="inline-flex min-h-10 w-full items-center justify-center gap-2 text-sm font-bold text-brand-700 hover:text-brand-900" href="{{ route('participant.learning.show', $enrollment) }}">Ver formación y encuesta <span aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-white px-6 py-12 text-center">
                        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700"><x-portal-nav-icon name="courses" /></span>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Tu próximo logro empieza aquí</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Todavía no tienes formación completada. Cuando termines un curso, minicurso o taller, aparecerá aquí con su imagen y tu calificación.</p>
                        <a class="mt-5 inline-flex min-h-11 items-center justify-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-700" href="{{ route('participant.catalog.index') }}">Explorar formación</a>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $completedEnrollments->links() }}</div>
        </section>
    </div>
@endsection
