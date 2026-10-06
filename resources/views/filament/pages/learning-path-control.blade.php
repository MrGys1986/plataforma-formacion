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
                <p class="pf-dashboard-eyebrow">Control de la ruta</p>

                <div class="pf-control-actions">
                    <a href="{{ $editUrl }}" class="pf-program-back">
                        <x-filament::icon
                            :icon="\Filament\Support\Icons\Heroicon::OutlinedPencilSquare"
                            class="pf-program-back-icon"
                        />
                        <span>Editar información</span>
                    </a>

                    <a href="{{ $pathsUrl }}" class="pf-program-back">
                        <x-filament::icon
                            :icon="\Filament\Support\Icons\Heroicon::OutlinedArrowLeft"
                            class="pf-program-back-icon"
                        />
                        <span>Volver a rutas</span>
                    </a>
                </div>
            </div>

            <h2 class="pf-edition-summary-title">{{ $learningPath->name }}</h2>
            <p class="pf-edition-summary-copy">
                Administra diplomados, certificaciones, programas de formación y participantes desde esta ruta.
            </p>
        </div>

        <dl class="pf-edition-meta">
            <div>
                <dt>Tipo</dt>
                <dd>Ruta de aprendizaje</dd>
            </div>
            <div>
                <dt>Área responsable</dt>
                <dd>{{ $learningPath->area?->name ?? 'Sin asignar' }}</dd>
            </div>
            <div>
                <dt>Duración</dt>
                <dd>{{ number_format((float) $learningPath->total_hours, 0) }} horas</dd>
            </div>
            <div>
                <dt>Avance</dt>
                <dd>{{ $learningPath->is_sequential ? 'Secuencial' : 'Libre' }}</dd>
            </div>
            <div>
                <dt>Estado</dt>
                <dd>{{ ucfirst($learningPath->status) }}</dd>
            </div>
        </dl>
    </section>

    @if (filled($learningPath->competencies))
        <section class="pf-edition-summary">
            <div class="pf-edition-summary-main">
                <h3 class="pf-edition-summary-title">Competencias que desarrolla</h3>
                <p class="pf-edition-summary-copy" style="white-space: pre-line">{{ $learningPath->competencies }}</p>
            </div>
        </section>
    @endif

    <div class="pf-edition-grid pf-learning-path-grid">
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
