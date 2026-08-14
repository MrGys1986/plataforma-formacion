@extends('layouts.area-manager')

@section('content')
    <x-portal-page
        title="Participantes del área"
        description="Usuarios adscritos a tu área: {{ $users->total() }}"
    >
        <div class="overflow-hidden rounded-xl border border-slate-200">
            <div class="hidden grid-cols-[minmax(0,1.5fr)_minmax(0,1.5fr)_minmax(0,1fr)_auto] gap-4 bg-slate-50 px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 md:grid">
                <span>Usuario</span>
                <span>Correo</span>
                <span>Rol</span>
                <span>Estado</span>
            </div>

            @forelse ($users as $user)
                <article class="grid gap-3 border-t border-slate-200 px-5 py-4 first:border-t-0 md:grid-cols-[minmax(0,1.5fr)_minmax(0,1.5fr)_minmax(0,1fr)_auto] md:items-center md:gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="mt-0.5 text-xs text-slate-500 md:hidden">{{ $user->area?->name ?? 'Sin área asignada' }}</p>
                        </div>
                    </div>
                    <p class="truncate text-sm text-slate-600">
                        <span class="font-semibold text-slate-500 md:hidden">Correo: </span>{{ $user->email }}
                    </p>
                    <p class="text-sm text-slate-600">
                        <span class="font-semibold text-slate-500 md:hidden">Rol: </span>{{ $user->getRoleNames()->first() ?? 'Sin rol' }}
                    </p>
                    <span @class([
                        'w-fit rounded-full px-3 py-1 text-xs font-semibold',
                        'bg-emerald-100 text-emerald-700' => $user->status === 'activo',
                        'bg-slate-100 text-slate-600' => $user->status !== 'activo',
                    ])>
                        {{ ucfirst($user->status ?? 'sin estado') }}
                    </span>
                </article>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay participantes registrados en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Asigna usuarios a esta área desde la administración para que aparezcan aquí.</p>
                </div>
            @endforelse
        </div>

        @if ($users->hasPages())
            <div class="mt-6">{{ $users->links() }}</div>
        @endif
    </x-portal-page>
@endsection
