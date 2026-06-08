@extends('layouts.participant')

@section('content')
    <x-portal-page :title="$survey->name" :description="'Actividad: '.$activity->name">
        @foreach ($survey->questions as $question)
            <div class="mb-4 rounded-lg border p-4">{{ $question->question_text }}</div>
        @endforeach
        <p class="text-sm text-slate-500">El envío de respuestas se habilitará en la siguiente etapa.</p>
    </x-portal-page>
@endsection
