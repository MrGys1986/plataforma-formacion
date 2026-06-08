@props(['title', 'description' => null])

<section class="rounded-xl bg-white p-6 shadow-sm">
    <div class="mb-6 border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-semibold">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>

    {{ $slot }}
</section>
