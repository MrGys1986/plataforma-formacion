@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mi formación" description="Cursos en los que participas, avance y evidencias propias.">
        @forelse ($enrollments as $enrollment)
            <a class="mb-3 block rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-md" href="{{ route('participant.learning.show', $enrollment) }}">
                <p class="font-semibold text-slate-900">{{ $enrollment->activity?->name }}</p>
                <div class="mt-2 flex flex-wrap gap-2 text-sm">
                    <span class="rounded-full bg-blue-50 px-3 py-1 font-semibold text-blue-700">{{ ucfirst($enrollment->status) }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">{{ ucfirst(str_replace('_', ' ', $enrollment->completion_status)) }}</span>
                </div>
            </a>
        @empty
            <p class="text-slate-500">Aún no tienes inscripciones.</p>
        @endforelse
        {{ $enrollments->links() }}
    </x-portal-page>
@endsection
