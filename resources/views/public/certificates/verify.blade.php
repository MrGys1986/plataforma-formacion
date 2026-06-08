@extends('layouts.public')

@section('content')
    <x-portal-page title="Verificación de constancia" description="La constancia fue localizada en el registro institucional.">
        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Folio</dt><dd>{{ $certificate->folio }}</dd></div>
            <div><dt class="font-semibold">Titular</dt><dd>{{ $certificate->user?->name }}</dd></div>
            <div><dt class="font-semibold">Actividad</dt><dd>{{ $certificate->activity?->name ?? 'No aplica' }}</dd></div>
            <div><dt class="font-semibold">Estado</dt><dd>{{ ucfirst($certificate->status) }}</dd></div>
            <div><dt class="font-semibold">Tipo</dt><dd>{{ ucfirst($certificate->certificate_type) }}</dd></div>
            <div><dt class="font-semibold">Fecha de emisión</dt><dd>{{ $certificate->issued_at?->format('d/m/Y') ?? 'Pendiente' }}</dd></div>
            <div><dt class="font-semibold">Institución emisora</dt><dd>{{ config('app.name') }}</dd></div>
        </dl>
    </x-portal-page>
@endsection
