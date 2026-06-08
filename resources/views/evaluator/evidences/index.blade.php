@extends('layouts.evaluator')

@section('content')
    <x-portal-page title="Evidencias pendientes">
        @forelse ($evidences as $evidence)
            <a class="mb-3 block rounded-lg border p-4" href="{{ route('evaluator.evidences.show', $evidence) }}">{{ $evidence->title }}</a>
        @empty
            <p class="text-slate-500">No hay evidencias pendientes.</p>
        @endforelse
        {{ $evidences->links() }}
    </x-portal-page>
@endsection
