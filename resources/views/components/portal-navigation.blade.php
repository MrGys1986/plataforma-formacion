@props(['items' => [], 'variant' => 'surface'])

@foreach($items as $item)
    @if(($item['type'] ?? 'link') === 'section')
        @if($variant === 'surface')
            <div class="px-2 pb-1 pt-4 first:pt-1">
                <p class="border-t border-slate-100 pt-4 text-[0.66rem] font-extrabold uppercase tracking-[0.18em] text-slate-400">{{ $item['label'] }}</p>
            </div>
        @else
            <span class="mx-1 h-6 w-px shrink-0 bg-white/15" aria-hidden="true"></span>
        @endif
    @else
        @php
            $href = route($item['route'], $item['parameters'] ?? []);
            $active = array_key_exists('section', $item)
                ? request()->routeIs('dashboard') && request()->query('section') === $item['section']
                : request()->routeIs(...$item['active']);
        @endphp
        <x-portal-nav-link
            :href="$href"
            :active="$active"
            :icon="$item['icon']"
            :variant="$variant"
        >
            {{ $item['label'] }}
        </x-portal-nav-link>
    @endif
@endforeach
