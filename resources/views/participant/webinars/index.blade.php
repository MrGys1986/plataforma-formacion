@extends('layouts.participant')

@section('content')
    <x-portal-page title="Webinars" description="Sesiones virtuales y grabaciones disponibles.">
        @forelse ($webinars as $webinar)
            <div class="mb-3 rounded-lg border p-4"><p class="font-semibold">{{ $webinar->title }}</p></div>
        @empty
            <p class="text-slate-500">No hay webinars publicados.</p>
        @endforelse
        {{ $webinars->links() }}
    </x-portal-page>
@endsection
