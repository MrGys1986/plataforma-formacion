@props(['label', 'value', 'detail' => null, 'tone' => 'blue'])

@php
    $bars = ['blue' => 'bg-blue-600', 'emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'rose' => 'bg-rose-500'];
    $percentage = max(0, min(100, (int) $value));
@endphp

<div>
    <div class="mb-2 flex items-end justify-between gap-4">
        <div><p class="text-sm font-semibold text-slate-800">{{ $label }}</p>@if($detail)<p class="mt-0.5 text-xs text-slate-500">{{ $detail }}</p>@endif</div>
        <span class="text-sm font-bold text-slate-900">{{ $percentage }}%</span>
    </div>
    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full rounded-full {{ $bars[$tone] ?? $bars['blue'] }}" style="width: {{ $percentage }}%"></div>
    </div>
</div>
