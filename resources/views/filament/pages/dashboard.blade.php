<x-filament-panels::page>
    <section class="pf-dashboard-hero">
        <span class="pf-dashboard-hero-icon">
            <x-filament::icon icon="heroicon-o-squares-2x2" />
        </span>

        <div>
            <p class="pf-dashboard-eyebrow">Panel administrativo · {{ $dashboardRole }}</p>
            <h1 class="pf-dashboard-title">Todo el control operativo en un solo lugar</h1>
            <p class="pf-dashboard-copy">
                Consulta el panorama general y accede rápidamente a las funciones disponibles para tu rol.
            </p>
        </div>
    </section>

    <div class="pf-dashboard-widgets">
        {{ $this->content }}
    </div>

    <div class="pf-dashboard-columns">
        <section class="pf-dashboard-panel">
            <header class="pf-dashboard-panel-header">
                <span class="pf-dashboard-panel-icon">
                    <x-filament::icon icon="heroicon-o-bolt" />
                </span>

                <div>
                    <h2 class="pf-dashboard-panel-title">Accesos rápidos</h2>
                    <p class="pf-dashboard-panel-subtitle">
                        Entra directamente a las tareas que utilizas con mayor frecuencia.
                    </p>
                </div>
            </header>

            <div class="pf-dashboard-links">
                @forelse ($quickLinks as $link)
                    <a href="{{ $link['url'] }}" class="pf-dashboard-link-card">
                        <div class="pf-dashboard-link-icon-wrap">
                            <x-filament::icon :icon="$link['icon']" class="pf-dashboard-link-icon" />
                        </div>

                        <div>
                            <p class="pf-dashboard-link-title">{{ $link['title'] }}</p>
                            <p class="pf-dashboard-link-copy">{{ $link['description'] }}</p>
                        </div>
                    </a>
                @empty
                    <p class="pf-dashboard-empty">No hay accesos administrativos disponibles para este rol.</p>
                @endforelse
            </div>
        </section>

        <section class="pf-dashboard-panel pf-dashboard-panel-attention">
            <header class="pf-dashboard-panel-header">
                <span class="pf-dashboard-panel-icon pf-dashboard-panel-icon-attention">
                    <x-filament::icon icon="heroicon-o-exclamation-circle" />
                </span>

                <div>
                    <h2 class="pf-dashboard-panel-title">Atención requerida</h2>
                    <p class="pf-dashboard-panel-subtitle">
                        Pendientes e indicadores que conviene revisar primero.
                    </p>
                </div>
            </header>

            <div class="pf-dashboard-attention-list">
                @forelse ($attentionItems as $item)
                    <a href="{{ $item['url'] }}" class="pf-dashboard-attention-item">
                        <span class="pf-dashboard-attention-label">{{ $item['label'] }}</span>
                        <span class="pf-dashboard-attention-badge">{{ number_format($item['count']) }}</span>
                    </a>
                @empty
                    <p class="pf-dashboard-empty">No hay pendientes dentro de tu ámbito.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-filament-panels::page>
