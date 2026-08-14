@extends('layouts.participant')

@section('content')
    <x-portal-page title="Recursos digitales" description="Materiales institucionales de apoyo.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($resources as $resource)
            <article class="rounded-xl border border-slate-200 p-5"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ ucfirst($resource->resource_type) }}</span><h2 class="mt-3 font-semibold">{{ $resource->title }}</h2><p class="mt-2 text-sm text-slate-500">{{ $resource->description ?: 'Material de apoyo institucional.' }}</p><div class="mt-4 flex gap-3">@if($resource->fileUpload)<a class="text-sm font-semibold text-blue-700" href="{{ $resource->fileUpload->temporaryDownloadUrl() }}">Descargar</a>@endif @if($resource->external_url)<a class="text-sm font-semibold text-blue-700" href="{{ $resource->external_url }}" target="_blank" rel="noopener">Abrir enlace</a>@endif</div></article>
        @empty
            <p class="text-slate-500 md:col-span-2 xl:col-span-3">No hay recursos disponibles.</p>
        @endforelse
        </div>
        {{ $resources->links() }}
    </x-portal-page>
@endsection
