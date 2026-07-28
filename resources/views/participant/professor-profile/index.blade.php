@extends('layouts.participant')

@section('content')
    <x-portal-page title="Perfil docente" description="Historial institucional de evidencias y constancias asociadas a tu perfil.">
        <div class="grid gap-8 xl:grid-cols-2">
            <section>
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Evidencias e historial</h2>
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $evidences->total() }} registros</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($evidences as $evidence)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <p class="font-medium text-slate-900">{{ $evidence->title }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $evidence->activity?->name ?? 'Historial profesional' }}</p>
                            @if($evidence->fileUpload)
                                <a class="mt-3 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ $evidence->fileUpload->temporaryDownloadUrl() }}">Descargar</a>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">No hay evidencias registradas en tu perfil.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $evidences->links() }}</div>
            </section>

            <section>
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Constancias</h2>
                    <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">{{ $certificates->total() }} registros</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($certificates as $certificate)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <p class="font-medium text-slate-900">{{ $certificate->activity?->name ?? $certificate->certificate_type ?? 'Constancia institucional' }}</p>
                            <p class="mt-1 text-sm text-slate-500">Folio: {{ $certificate->folio }}</p>
                            @if($certificate->fileUpload)
                                <a class="mt-3 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-600" href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}">Descargar</a>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">No hay constancias registradas en tu perfil.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $certificates->links() }}</div>
            </section>
        </div>
    </x-portal-page>
@endsection
