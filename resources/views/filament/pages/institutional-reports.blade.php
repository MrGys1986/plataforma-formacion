<x-filament-panels::page>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($metrics as $label => $value)
            <x-filament::section>
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ number_format($value) }}</p>
            </x-filament::section>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <x-filament::section>
            <x-slot name="heading">Pagos validados</x-slot>
            <p class="text-3xl font-bold text-gray-950 dark:text-white">
                ${{ number_format((float) $validatedPayments, 2) }} MXN
            </p>
        </x-filament::section>

        <x-filament::section class="lg:col-span-2">
            <x-slot name="heading">Programas con actividad</x-slot>

            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse ($completionByProgram as $program)
                    <div class="flex items-center justify-between gap-4 py-3">
                        <div>
                            <p class="font-semibold text-gray-950 dark:text-white">{{ $program->name }}</p>
                            <p class="text-sm text-gray-500">{{ $program->activityType?->name }}</p>
                        </div>
                        <div class="text-right text-sm text-gray-600 dark:text-gray-300">
                            <p>{{ $program->editions_count }} ediciones</p>
                            <p>{{ $program->completed_enrollments_count }} con finalizaciones</p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-sm text-gray-500">Todavía no hay programas registrados.</p>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
