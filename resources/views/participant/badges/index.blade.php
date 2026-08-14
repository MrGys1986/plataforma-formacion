@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mis insignias" description="Reconocimientos digitales obtenidos por tus competencias y logros.">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($badges as $badge)
                @php
                    $courseIdentity = $badge->learningPath?->public_id ?? $badge->learningPath?->slug ?? $badge->learningPath?->id ?? $badge->name;
                    $courseColors = ['#7c3aed', '#0891b2', '#ea580c', '#059669', '#db2777', '#ca8a04', '#4f46e5', '#be123c'];
                    $courseColor = $courseColors[abs(crc32((string) $courseIdentity)) % count($courseColors)];
                @endphp
                <a class="group rounded-2xl border border-slate-200 bg-white p-6 text-center transition hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg"
                   href="{{ route('participant.badges.show', $badge) }}" style="border-top: 4px solid {{ $courseColor }};">
                    <x-course-badge-emblem :badge="$badge" />
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em]" style="color: {{ $courseColor }};">Ruta completada</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $badge->name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $badge->learningPath?->name ?? 'Ruta de aprendizaje' }}</p>
                    <p class="mt-4 text-xs text-slate-400">Emitida {{ $badge->issued_at?->format('d/m/Y') }}</p>
                </a>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                    Todavía no tienes insignias emitidas.
                </p>
            @endforelse
        </div>
        <div class="mt-6">{{ $badges->links() }}</div>
    </x-portal-page>
@endsection
