@props(['badge', 'size' => 'md'])

@php
    $course = $badge->activity;
    $learningPath = $badge->learningPath;
    $source = $learningPath ?? $course;
    $identity = $source?->public_id ?? $source?->slug ?? $source?->id ?? $source?->name ?? $badge->name;
    $variant = abs(crc32((string) $identity));
    $palettes = [
        ['#7c3aed', '#2563eb', '#ede9fe'], ['#0891b2', '#0f766e', '#cffafe'],
        ['#ea580c', '#dc2626', '#ffedd5'], ['#059669', '#65a30d', '#d1fae5'],
        ['#db2777', '#9333ea', '#fce7f3'], ['#ca8a04', '#b45309', '#fef3c7'],
        ['#4f46e5', '#0891b2', '#e0e7ff'], ['#be123c', '#7e22ce', '#ffe4e6'],
    ];
    [$from, $to, $soft] = $palettes[$variant % count($palettes)];
    $shapes = ['50%', '28%', '50% 36% 50% 36%', '32% 50% 32% 50%'];
    $shape = $shapes[($variant >> 3) % count($shapes)];
    $symbols = ['✦', '◆', '▲', '✺', '⬢', '●', '✧', '■'];
    $symbol = $symbols[($variant >> 5) % count($symbols)];
    $words = preg_split('/\s+/u', trim($source?->name ?? $badge->name), -1, PREG_SPLIT_NO_EMPTY);
    $initials = collect($words)->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
    $coverFile = $course?->coverFile ?? $course?->trainingProgram?->coverFile;
    $dimensions = $size === 'lg' ? 'h-40 w-40' : 'h-28 w-28';
    $textSize = $size === 'lg' ? 'text-4xl' : 'text-2xl';
@endphp

<div {{ $attributes->class(["relative mx-auto flex {$dimensions} items-center justify-center p-2 shadow-xl"]) }}
    style="border-radius: {{ $shape }}; background: linear-gradient(135deg, {{ $from }}, {{ $to }}); box-shadow: 0 16px 30px -15px {{ $from }};"
    title="Diseño de la ruta {{ $source?->name ?? $badge->name }}">
    <div class="relative flex h-full w-full items-center justify-center overflow-hidden border-2 border-white/75" style="border-radius: {{ $shape }}; background-color: {{ $soft }};">
        @if($coverFile)
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ $coverFile->optimizedImageUrl(420, 420) }}" alt="Portada de {{ $course->name }}">
            <span class="absolute inset-0" style="background: linear-gradient(145deg, {{ $from }}99, {{ $to }}cc);"></span>
        @else
            <span class="absolute -right-5 -top-5 h-20 w-20 rounded-full border-[12px] border-white/20"></span>
            <span class="absolute -bottom-5 -left-5 h-16 w-16 rotate-45 border-[10px] border-white/15"></span>
        @endif
        <span class="relative flex flex-col items-center text-white drop-shadow-md">
            <span class="{{ $textSize }} font-black leading-none">{{ $symbol }}</span>
            <span class="mt-1 text-xs font-extrabold tracking-[0.18em]">{{ $initials }}</span>
        </span>
    </div>
</div>
