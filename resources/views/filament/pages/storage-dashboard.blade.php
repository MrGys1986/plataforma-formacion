<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($metrics as $label => $value)
            <x-filament::section><p class="text-sm text-gray-500">{{ $label }}</p><p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">{{ $value }}</p></x-filament::section>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-filament::section><x-slot name="heading">Distribución por tipo</x-slot><div class="divide-y divide-gray-200 dark:divide-white/10">@forelse($byType as $item)<div class="flex items-center justify-between gap-4 py-3"><span class="truncate text-sm">{{ $item->type }}</span><span class="text-sm font-semibold">{{ $item->total }} archivos · {{ number_format($item->bytes / 1024, 1) }} KB</span></div>@empty<p class="text-sm text-gray-500">No hay archivos registrados.</p>@endforelse</div></x-filament::section>
        <x-filament::section><x-slot name="heading">Archivos huérfanos</x-slot><p class="mb-3 text-sm text-gray-500">No están vinculados con ningún registro. Se pueden enviar a retención con <code>files:cleanup-orphans --execute</code>.</p><div class="divide-y divide-gray-200 dark:divide-white/10">@forelse($orphans as $file)<div class="py-3"><p class="truncate text-sm font-medium">{{ $file->original_name }}</p><p class="mt-1 text-xs text-gray-500">{{ $file->disk }} · {{ $file->created_at?->format('d/m/Y H:i') }}</p></div>@empty<p class="text-sm text-gray-500">No se detectaron archivos huérfanos.</p>@endforelse</div></x-filament::section>
    </div>

    <x-filament::section><x-slot name="heading">Últimas cargas</x-slot><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="text-xs uppercase text-gray-500"><tr><th class="px-3 py-2">Archivo</th><th class="px-3 py-2">Ubicación</th><th class="px-3 py-2">Tipo</th><th class="px-3 py-2">Usuario</th><th class="px-3 py-2">Fecha</th></tr></thead><tbody class="divide-y divide-gray-200 dark:divide-white/10">@foreach($recent as $file)<tr><td class="px-3 py-3 font-medium">{{ $file->original_name }}</td><td class="px-3 py-3">{{ $file->disk }}</td><td class="px-3 py-3">{{ $file->mime_type ?: '—' }}</td><td class="px-3 py-3">{{ $file->uploadedBy?->name ?? 'Sistema' }}</td><td class="px-3 py-3">{{ $file->created_at?->format('d/m/Y H:i') }}</td></tr>@endforeach</tbody></table></div></x-filament::section>
</x-filament-panels::page>
