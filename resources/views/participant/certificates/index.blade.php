@extends('layouts.portal')

@section('title', 'Mis constancias | Plataforma de Formación')

@section('content')
    <div class="space-y-7">
        <header class="relative overflow-hidden rounded-3xl bg-brand-950 shadow-xl shadow-brand-950/10">
            <div class="pointer-events-none absolute -right-12 -top-24 h-80 w-80 rounded-full border-[36px] border-white/5" aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 right-0 h-44 w-96 bg-gradient-to-tl from-brand-500/25 to-transparent" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-7 p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex min-w-0 flex-col gap-5 sm:flex-row sm:items-center">
                    <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-brand-700 text-white ring-4 ring-white/10" aria-hidden="true">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3M7 7h10M7 10h4"/><circle cx="12" cy="15" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="m10 17.5-1 4 3-1.5 3 1.5-1-4"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-200">Mi trayectoria</p>
                        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Mis constancias</h1>
                        <p class="mt-3 max-w-lg text-sm leading-6 text-brand-100/85">Cada curso terminado es un nuevo logro. Aquí puedes consultar y descargar tus constancias emitidas por la institución.</p>
                    </div>
                </div>
                <div class="flex shrink-0 flex-wrap items-center gap-5">
                    <div class="rounded-2xl border border-white/15 bg-white/[0.07] px-5 py-4">
                        <p class="text-3xl font-extrabold tabular-nums text-white">{{ $certificates->total() }}</p>
                        <p class="mt-1 text-xs font-medium text-brand-100">{{ $certificates->total() === 1 ? 'Constancia emitida' : 'Constancias emitidas' }}</p>
                    </div>
                    <a class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-900 shadow-sm hover:bg-brand-50 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white" href="{{ route('participant.profile') }}">
                        <x-portal-nav-icon name="users" />
                        Mi perfil
                    </a>
                </div>
            </div>
        </header>

        <section aria-labelledby="certificates-heading">
            <div class="mb-5">
                <h2 id="certificates-heading" class="text-xl font-extrabold tracking-tight text-slate-900">Tu colección de logros</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">Cursos, minicursos y talleres que respaldan tu formación.</p>
            </div>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($certificates as $certificate)
                    @php
                        $activity = $certificate->activity;
                        $name = $activity?->participant_name ?? $certificate->certificate_type ?? 'Constancia de formación';
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
                    @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-950/10">
                        <div class="relative aspect-video overflow-hidden bg-brand-50">
                            <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transform-none" src="{{ $coverFile?->optimizedImageUrl(960, 540) ?? asset('img/courses/'.$fallbackCover) }}" alt="Portada de {{ $name }}" loading="lazy" decoding="async">
                            <span class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-950/70 to-transparent" aria-hidden="true"></span>
                            <span class="absolute bottom-3 left-4 text-xs font-bold uppercase tracking-wider text-white">{{ $activity?->activityType?->name ?? 'Formación' }}</span>
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 shadow-sm">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                                Emitida
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="break-words text-lg font-extrabold leading-snug tracking-tight text-slate-900">{{ $name }}</h3>
                            <dl class="mt-5 space-y-3 rounded-xl bg-slate-50 px-4 py-3">
                                <div>
                                    <dt class="text-xs font-medium text-slate-500">Folio</dt>
                                    <dd class="mt-1 break-all text-sm font-bold text-slate-700">{{ $certificate->folio }}</dd>
                                </div>
                                @if ($certificate->issued_at)
                                    <div>
                                        <dt class="text-xs font-medium text-slate-500">Fecha de emisión</dt>
                                        <dd class="mt-1 text-sm font-bold text-slate-700"><time datetime="{{ $certificate->issued_at->toDateString() }}">{{ $certificate->issued_at->format('d/m/Y') }}</time></dd>
                                    </div>
                                @endif
                            </dl>
                            <div class="mt-auto pt-5">
                                @if ($certificate->fileUpload)
                                    <a class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600" href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}" aria-label="Descargar constancia de {{ $name }}">
                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 16v4h14v-4"/></svg>
                                        Descargar constancia
                                    </a>
                                @else
                                    <p class="rounded-xl border border-dashed border-slate-200 px-3 py-3 text-center text-xs font-medium text-slate-500">El documento todavía no está disponible.</p>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-white px-6 py-12 text-center">
                        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-700"><x-portal-nav-icon name="certificate" /></span>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Tu primera constancia te espera</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">No hay constancias emitidas. Cuando la institución emita la tuya, aparecerá aquí con la imagen de tu curso y podrás descargarla.</p>
                        <a class="mt-5 inline-flex min-h-11 items-center justify-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-700" href="{{ route('participant.my-courses.index') }}">Ver mi formación</a>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $certificates->links() }}</div>
        </section>
    </div>
@endsection
