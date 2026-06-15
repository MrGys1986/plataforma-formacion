<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            {{ $fileName ?? 'Registro de aplicación' }}
        </x-slot>

        <p class="mb-4 text-sm text-gray-500">
            Se muestran los eventos más recientes del archivo. Los fallos de trabajos en cola se consultan en su apartado independiente.
        </p>

        <pre class="max-h-[65vh] overflow-auto whitespace-pre-wrap rounded-xl bg-gray-950 p-5 text-xs leading-5 text-gray-100">{{ $contents }}</pre>
    </x-filament::section>
</x-filament-panels::page>
