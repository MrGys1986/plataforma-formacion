@props(['label', 'value', 'detail' => null, 'tone' => 'blue'])

@php
    $tones = [
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'slate' => 'bg-slate-50 text-slate-700 ring-slate-200',
    ];
@endphp

<article class="flex min-h-36 flex-col items-center justify-center rounded-2xl p-5 text-center shadow-sm ring-1 transition duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $tones[$tone] ?? $tones['blue'] }}">
    <p class="text-sm font-semibold opacity-80">{{ $label }}</p>
    <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $value }}</p>
    @if ($detail)<p class="mt-2 text-xs font-medium opacity-70">{{ $detail }}</p>@endif
</article>
