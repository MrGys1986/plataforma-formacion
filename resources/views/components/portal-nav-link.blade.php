@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    @class([
        'block rounded-lg border px-4 py-3 font-medium transition',
        'border-blue-100 bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' => $active,
        'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-blue-700' => ! $active,
    ])
>
    {{ $slot }}
</a>
