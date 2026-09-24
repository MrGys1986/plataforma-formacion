@props(['href', 'active' => false, 'icon' => 'squares', 'variant' => 'surface'])

<a
    href="{{ $href }}"
    @class([
        'group/nav relative flex items-center gap-3 overflow-hidden rounded-xl border font-semibold transition duration-200',
        'min-h-12 px-3 py-2.5' => $variant === 'surface',
        'min-h-11 shrink-0 px-3 py-2 text-sm' => $variant === 'header',
        'border-brand-200 bg-gradient-to-r from-brand-100 to-brand-50 text-brand-800 shadow-sm ring-1 ring-brand-100' => $variant === 'surface' && $active,
        'border-transparent text-slate-600 hover:border-brand-100 hover:bg-brand-50 hover:text-brand-700' => $variant === 'surface' && ! $active,
        'border-white/20 bg-white text-brand-900 shadow-lg shadow-brand-950/20' => $variant === 'header' && $active,
        'border-transparent text-blue-100 hover:border-white/15 hover:bg-white/10 hover:text-white' => $variant === 'header' && ! $active,
    ])
>
    @if($active && $variant === 'surface')<span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-brand-600"></span>@endif
    <span @class([
        'flex shrink-0 items-center justify-center rounded-lg transition',
        'h-9 w-9' => $variant === 'surface',
        'h-7 w-7' => $variant === 'header',
        'bg-brand-600 text-white shadow-md shadow-brand-600/20' => $variant === 'surface' && $active,
        'bg-slate-100 text-slate-500 group-hover/nav:bg-white group-hover/nav:text-brand-700' => $variant === 'surface' && ! $active,
        'bg-brand-50 text-brand-700' => $variant === 'header' && $active,
        'bg-white/10 text-blue-100 group-hover/nav:bg-white/15 group-hover/nav:text-white' => $variant === 'header' && ! $active,
    ])>
        <x-portal-nav-icon :name="$icon" @class(['h-5 w-5' => $variant === 'surface', 'h-4 w-4' => $variant === 'header']) />
    </span>
    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>
    @if($variant === 'surface')
        <svg class="h-4 w-4 shrink-0 text-brand-500 opacity-0 transition group-hover/nav:translate-x-0.5 group-hover/nav:opacity-100 {{ $active ? 'opacity-100' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
    @endif
</a>
