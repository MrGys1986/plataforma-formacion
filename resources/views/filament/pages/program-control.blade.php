<x-filament-panels::page>
    <nav class="pf-program-breadcrumbs" aria-label="Ruta de navegación">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (! $loop->first)
                <span class="pf-program-breadcrumb-separator" aria-hidden="true">/</span>
            @endif

            @if ($breadcrumb['url'])
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            @else
                <span class="pf-program-breadcrumb-current" aria-current="page">{{ $breadcrumb['label'] }}</span>
            @endif
        @endforeach
    </nav>

    <section class="pf-edition-summary">
        <div class="pf-edition-summary-main">
            <div class="pf-program-control-toolbar">
                <p class="pf-dashboard-eyebrow">Control del programa</p>

                <a href="{{ $programsUrl }}" class="pf-program-back">
                    <x-filament::icon
                        :icon="\Filament\Support\Icons\Heroicon::OutlinedArrowLeft"
                        class="pf-program-back-icon"
                    />
                    <span>Volver a programas</span>
                </a>
            </div>

            <h2 class="pf-edition-summary-title">{{ $program->name }}</h2>
            <p class="pf-edition-summary-copy">
                Administra desde aquí inscripciones, asistencia, evidencias, evaluaciones, encuesta de satisfacción,
                archivos y constancias desde un único nivel de control.
            </p>
        </div>

        <dl class="pf-edition-meta">
            <div>
                <dt>Tipo</dt>
                <dd>{{ $program->activityType?->name ?? 'Sin definir' }}</dd>
            </div>
            <div>
                <dt>Modalidad</dt>
                <dd>{{ $program->default_modality ?? 'Sin definir' }}</dd>
            </div>
            <div>
                <dt>Duración</dt>
                <dd>{{ number_format((float) $program->duration_hours, 0) }} horas</dd>
            </div>
            <div>
                <dt>Área responsable</dt>
                <dd>{{ $program->area?->name ?? 'Sin asignar' }}</dd>
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
