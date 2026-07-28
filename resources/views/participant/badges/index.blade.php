@extends('layouts.participant')

@section('content')
    <x-portal-page title="Mis insignias" description="Reconocimientos digitales obtenidos por tus competencias y logros.">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($badges as $badge)
                <a class="group rounded-2xl border border-slate-200 bg-white p-6 text-center transition hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg"
                   href="{{ route('participant.badges.show', $badge) }}">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-violet-600 to-blue-600 p-2 shadow-lg shadow-violet-200">
                        <div class="flex h-full w-full items-center justify-center rounded-full border-2 border-white/70 text-4xl text-white">★</div>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-violet-600">Insignia institucional</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $badge->name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $badge->activity?->name ?? 'Logro institucional' }}</p>
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
