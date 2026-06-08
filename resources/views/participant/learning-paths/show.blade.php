@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$learningPath->name" :description="$learningPath->description">
        @forelse ($learningPath->items as $item)
            <div class="mb-3 rounded-lg border p-4">
                <span class="mr-2 font-semibold">{{ $item->order_number }}.</span>{{ $item->activity?->name }}
            </div>
        @empty
            <p class="text-slate-500">Esta ruta aún no tiene actividades.</p>
        @endforelse
    </x-portal-page>
@endsection
