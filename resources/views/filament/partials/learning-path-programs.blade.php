<x-filament::section heading="Programas agregados a la ruta">
    @if ($programs->isEmpty())
        <p>Esta ruta aún no tiene cursos, minicursos ni talleres. Usa las opciones de abajo para agregarlos.</p>
    @else
        <ul class="space-y-3">
            @foreach ($programs as $program)
                <li wire:key="learning-path-program-{{ $program->id }}">
                    <span class="font-semibold">{{ $program->name }}</span>
                    <span> · {{ $program->activityType?->name }}</span>
                    <span> · {{ $program->pivot->is_required ? 'Obligatorio' : 'Opcional' }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</x-filament::section>
