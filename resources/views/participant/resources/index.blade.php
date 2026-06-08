@extends('layouts.participant')

@section('content')
    <x-portal-page title="Recursos digitales" description="Materiales institucionales de apoyo.">
        @forelse ($resources as $resource)
            <div class="mb-3 rounded-lg border p-4"><p class="font-semibold">{{ $resource->title }}</p></div>
        @empty
            <p class="text-slate-500">No hay recursos disponibles.</p>
        @endforelse
        {{ $resources->links() }}
    </x-portal-page>
@endsection
