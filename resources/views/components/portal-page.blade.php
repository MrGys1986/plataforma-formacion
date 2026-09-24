@props(['title', 'description' => null])

<section class="portal-surface overflow-hidden rounded-3xl border border-brand-100 bg-white p-4 shadow-xl shadow-brand-950/5 sm:p-6 lg:p-8">
    <div class="relative mb-7 overflow-hidden rounded-2xl border border-brand-100 bg-gradient-to-r from-brand-50 to-white px-5 py-5 sm:px-6">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-brand-600"></div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-600">Plataforma institucional</p>
        <h1 class="mt-1.5 text-2xl font-extrabold tracking-tight text-brand-950 sm:text-3xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">{{ $description }}</p>
        @endif
    </div>

    {{ $slot }}
</section>
