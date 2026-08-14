@extends('layouts.rh')

@section('content')
<x-portal-page title="Personal" description="Directorio y seguimiento de formación del personal institucional.">
    <form class="mb-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_220px_180px_auto]" method="GET" action="{{ route('rh.staff.index') }}">
        <label>
            <span class="sr-only">Buscar personal</span>
            <input class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o correo">
        </label>
        <label>
            <span class="sr-only">Filtrar por área</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="area">
                <option value="">Todas las áreas</option>
                @foreach($areas as $area)<option value="{{ $area->id }}" @selected((string) request('area') === (string) $area->id)>{{ $area->name }}</option>@endforeach
            </select>
        </label>
        <label>
            <span class="sr-only">Filtrar por estado</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="status">
                <option value="">Todos los estados</option>
                <option value="activo" @selected(request('status') === 'activo')>Activo</option>
                <option value="inactivo" @selected(request('status') === 'inactivo')>Inactivo</option>
            </select>
        </label>
        <div class="flex gap-2">
            <button class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800" type="submit">Filtrar</button>
            @if(request()->hasAny(['search','area','status']))<a class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100" href="{{ route('rh.staff.index') }}">Limpiar</a>@endif
        </div>
    </form>

    <div class="mb-4 flex items-center justify-between gap-4">
        <p class="text-sm text-slate-500"><span class="font-semibold text-slate-800">{{ $users->total() }}</span> usuarios encontrados</p>
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        @forelse($users as $user)
        <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-md" href="{{ route('rh.staff.show', $user) }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                    <div class="min-w-0"><h2 class="truncate font-semibold text-slate-900 group-hover:text-blue-700">{{ $user->name }}</h2><p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p></div>
                </div>
                <span @class(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => $user->status === 'activo','bg-slate-100 text-slate-600' => $user->status !== 'activo'])>{{ ucfirst($user->status ?? 'sin estado') }}</span>
            </div>
            <div class="mt-5 grid gap-3 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rol</p><p class="mt-1 font-medium text-slate-700">{{ $user->getRoleNames()->join(', ') ?: 'Sin rol' }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Área</p><p class="mt-1 font-medium text-slate-700">{{ $user->area?->name ?? 'Sin área asignada' }}</p></div>
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm">
                <div class="flex gap-2"><span class="rounded-full bg-blue-50 px-3 py-1 font-semibold text-blue-700">{{ $user->enrollments_count }} capacitaciones</span><span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">{{ $user->certificates_count }} constancias</span></div>
                <span class="font-semibold text-blue-700">Ver expediente →</span>
            </div>
        </a>
        @empty<div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No se encontró personal con los filtros seleccionados.</div>@endforelse
    </div>
    @if($users->hasPages())<div class="mt-6">{{ $users->links() }}</div>@endif
</x-portal-page>
@endsection
