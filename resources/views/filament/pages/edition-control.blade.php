<x-filament-panels::page>
    <section class="pf-edition-summary">
        <div class="pf-edition-summary-main">
            <p class="pf-dashboard-eyebrow">Administración de la actividad</p>
            <h2 class="pf-edition-summary-title">{{ $edition->name }}</h2>
            <p class="pf-edition-summary-copy">
                Concentramos aquí la operación del curso o actividad para manejar inscripciones, asistencia,
                evidencias, evaluaciones, archivos y constancias sin tenerlas regadas en el menú.
            </p>
        </div>

        <dl class="pf-edition-meta">
            <div>
                <dt>Modalidad</dt>
                <dd>{{ $edition->modality ?? 'Sin definir' }}</dd>
            </div>
            <div>
                <dt>Fechas</dt>
                <dd>
                    {{ optional($edition->start_date)->format('d/m/Y') ?? 'Pendiente' }}
                    —
                    {{ optional($edition->end_date)->format('d/m/Y') ?? 'Pendiente' }}
                </dd>
            </div>
            <div>
                <dt>Área</dt>
                <dd>{{ $edition->area?->name ?? 'Sin asignar' }}</dd>
            </div>
            <div>
                <dt>Responsable</dt>
                <dd>{{ $edition->instructor?->name ?? 'Sin asignar' }}</dd>
            </div>
        </dl>
    </section>

    <div class="pf-edition-grid">
        @foreach ($cards as $card)
            <a href="{{ $card['url'] }}" class="pf-edition-card">
                <div class="pf-edition-card-head">
                    <div class="pf-edition-card-icon-wrap">
                        <x-filament::icon :icon="$card['icon']" class="pf-edition-card-icon" />
                    </div>
                    <span class="pf-edition-card-count">{{ number_format($card['count']) }}</span>
                </div>

                <div>
                    <h3 class="pf-edition-card-title">{{ $card['title'] }}</h3>
                    <p class="pf-edition-card-copy">{{ $card['description'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</x-filament-panels::page>
