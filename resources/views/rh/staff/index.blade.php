@extends('layouts.rh')

@section('content')
    <x-portal-page title="Personal">
        @forelse ($users as $user)
            <a class="mb-3 block rounded-lg border p-4" href="{{ route('rh.staff.show', $user) }}">{{ $user->name }}</a>
        @empty
            <p class="text-slate-500">No hay personal registrado.</p>
        @endforelse
        {{ $users->links() }}
    </x-portal-page>
@endsection
