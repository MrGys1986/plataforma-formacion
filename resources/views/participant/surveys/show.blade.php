@extends('layouts.portal')

@section('content')
    <x-portal-page :title="$survey->name" :description="'Actividad: '.$activity->participant_name">
        @if ($response)
            <p class="mb-4 text-sm text-emerald-700">Ya respondiste esta encuesta para esta actividad.</p>
        @else
            @if ($errors->any())
                <ul class="mb-4 text-sm text-rose-700" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form method="POST" action="{{ route('participant.surveys.store', ['survey' => $survey, 'activity' => $activity]) }}">
                @csrf
                @foreach ($survey->questions as $index => $question)
                    <div class="mb-4 rounded-lg border p-4">
                        <label for="answer-{{ $question->public_id }}" class="mb-2 block font-semibold">{{ $question->question_text }}{{ $question->is_required ? ' *' : '' }}</label>
                        <input type="hidden" name="answers[{{ $index }}][question_public_id]" value="{{ $question->public_id }}">
                        @if (in_array($question->question_type, ['escala', 'opcion_multiple'], true))
                            <select id="answer-{{ $question->public_id }}" name="answers[{{ $index }}][value]" class="w-full rounded-lg border border-slate-300 px-3 py-2" @required($question->is_required)>
                                <option value="">Selecciona una respuesta</option>
                                @foreach ($question->answerOptions() as $value => $label)
                                    <option value="{{ $value }}" @selected((string) old("answers.$index.value") === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @else
                            <textarea id="answer-{{ $question->public_id }}" name="answers[{{ $index }}][value]" rows="3" maxlength="2000" class="w-full rounded-lg border border-slate-300 px-3 py-2" @required($question->is_required)>{{ old("answers.$index.value") }}</textarea>
                        @endif
                    </div>
                @endforeach
                <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white">Enviar evaluación del curso</button>
            </form>
        @endif
    </x-portal-page>
@endsection
