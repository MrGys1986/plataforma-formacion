@extends('layouts.portal')

@section('content')
    <x-portal-page :title="$enrollment->activity?->participant_name ?? 'Mi formación'" description="Consulta tu avance y entrega tus propias evidencias.">
        @if(session('status'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="grid gap-4 sm:grid-cols-3">
            <x-quality-stat label="Inscripción" :value="ucfirst($enrollment->status)" detail="Estado actual" />
            <x-quality-stat label="Avance" :value="ucfirst(str_replace('_', ' ', $enrollment->completion_status))" detail="Progreso del curso" tone="emerald" />
            <x-quality-stat label="Calificación" :value="$enrollment->final_score ?? 'Pendiente'" detail="Resultado obtenido" tone="slate" />
        </div>

        @if ($enrollment->status === 'aprobada' && $enrollment->completion_status === 'completado')
            <section class="mt-6 rounded-lg border p-4">
                <h2 class="font-semibold">Formación completada</h2>
                @foreach ($enrollment->certificates as $certificate)
                    @if ($certificate->fileUpload)
                        <a class="mt-3 block text-sm font-semibold text-blue-700 underline" href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}">Descargar constancia</a>
                    @endif
                @endforeach
                @foreach ($surveys as $survey)
                    @if ($answeredSurveyIds->contains($survey->id))
                        <p class="mt-3 text-sm text-emerald-700">Encuesta respondida: {{ $survey->name }}</p>
                    @else
                        <a class="mt-3 block text-sm font-semibold text-blue-700 underline" href="{{ route('participant.surveys.show', ['survey' => $survey, 'activity' => $enrollment->activity]) }}">Evaluar curso: {{ $survey->name }}</a>
                    @endif
                @endforeach
            </section>
        @endif

        <div class="mt-7 grid gap-6 xl:grid-cols-[1fr_360px]">
            <section class="rounded-2xl border border-brand-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700"><x-portal-nav-icon name="evidence" /></span>
                    <div><h2 class="font-extrabold text-slate-900">Mis evidencias</h2><p class="mt-0.5 text-sm text-slate-500">Documentos entregados en este curso.</p></div>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($enrollment->evidences as $evidence)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $evidence->title }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ ucfirst($evidence->evidence_type) }}</p>
                                </div>
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ ucfirst($evidence->status) }}</span>
                            </div>
                            @if($evidence->fileUpload)
                                <a class="mt-3 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ $evidence->fileUpload->temporaryDownloadUrl() }}">Descargar archivo</a>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Aún no has cargado evidencias para este curso.</p>
                    @endforelse
                </div>

            </section>

            <section class="h-fit rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-white p-5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-brand-700 shadow-sm"><x-portal-nav-icon name="evidence" /></span>
                <h2 class="mt-3 font-extrabold text-slate-900">Subir evidencia</h2>
                <p class="mt-1 text-sm text-slate-500">Agrega tu archivo y la información necesaria.</p>
                @if($enrollment->status === 'aprobada')
                    <form class="mt-4 space-y-4" method="POST" action="{{ route('participant.evidences.store', $enrollment) }}" enctype="multipart/form-data">
                        @csrf
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Título</span>
                            <input class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="title" maxlength="255" required>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Tipo</span>
                            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="evidence_type" required>
                                <option value="producto">Producto</option>
                                <option value="participacion">Participación</option>
                                <option value="evaluacion">Evaluación</option>
                                <option value="otro">Otro</option>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Descripción</span>
                            <textarea class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="description" rows="3" maxlength="3000"></textarea>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Archivo</span>
                            <input class="block w-full text-sm text-slate-600" type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
                        </label>
                        <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Cargar evidencia</button>
                    </form>
                @else
                    <p class="mt-3 text-sm text-slate-500">Podrás subir evidencias cuando tu inscripción sea aprobada.</p>
                @endif
            </section>
        </div>
    </x-portal-page>
@endsection
