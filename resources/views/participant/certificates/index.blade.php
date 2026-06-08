@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mis constancias" description="Documentos emitidos por la institución.">
        @forelse ($certificates as $certificate)
            <div class="mb-3 rounded-lg border p-4">
                <p class="font-semibold">{{ $certificate->activity?->name ?? $certificate->certificate_type }}</p>
                <p class="text-sm text-slate-600">Folio: {{ $certificate->folio }}</p>
                @if ($certificate->fileUpload)
                    <a class="mt-2 inline-block text-sm font-semibold text-blue-700 underline"
                       href="{{ $certificate->fileUpload->temporaryDownloadUrl() }}">
                        Descargar constancia
                    </a>
                @endif
            </div>
        @empty
            <p class="text-slate-500">No hay constancias emitidas.</p>
        @endforelse
        {{ $certificates->links() }}
    </x-portal-page>
@endsection
