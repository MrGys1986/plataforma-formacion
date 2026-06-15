<x-filament-panels::page>
    <section class="pf-dashboard-hero">
        <div>
            <p class="pf-dashboard-eyebrow">Panel institucional</p>
            <h2 class="pf-dashboard-title">Todo el control operativo en un solo lugar</h2>
            <p class="pf-dashboard-copy">
                Entramos rápido a usuarios, oferta formativa y seguimiento sin brincar entre secciones sueltas.
            </p>
        </div>

        <div class="pf-dashboard-metrics">
            @foreach ($heroMetrics as $metric)
                <article class="pf-dashboard-metric-card">
                    <span class="pf-dashboard-metric-label">{{ $metric['label'] }}</span>
                    <strong class="pf-dashboard-metric-value">{{ number_format($metric['value']) }}</strong>
                </article>
            @endforeach
        </div>
    </section>

    <div class="pf-dashboard-columns">
        <section class="pf-dashboard-panel">
            <div class="pf-dashboard-panel-header">
                <div>
                    <p class="pf-dashboard-panel-kicker">Accesos rápidos</p>
                    <h3 class="pf-dashboard-panel-title">Lo que más movemos día a día</h3>
                </div>
            </div>

            <div class="pf-dashboard-links">
                @foreach ($quickLinks as $link)
                    <a href="{{ $link['url'] }}" class="pf-dashboard-link-card">
                        <div class="pf-dashboard-link-icon-wrap">
                            <x-filament::icon :icon="$link['icon']" class="pf-dashboard-link-icon" />
                        </div>

                        <div>
                            <p class="pf-dashboard-link-title">{{ $link['title'] }}</p>
                            <p class="pf-dashboard-link-copy">{{ $link['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="pf-dashboard-panel">
            <div class="pf-dashboard-panel-header">
                <div>
                    <p class="pf-dashboard-panel-kicker">Atención</p>
                    <h3 class="pf-dashboard-panel-title">Indicadores para no perder ritmo</h3>
                </div>
            </div>

            <div class="pf-dashboard-attention-list">
                @foreach ($attentionItems as $item)
                    <a href="{{ $item['url'] }}" class="pf-dashboard-attention-item">
                        <span class="pf-dashboard-attention-label">{{ $item['label'] }}</span>
                        <span class="pf-dashboard-attention-badge">{{ number_format($item['count']) }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    <div class="pf-dashboard-widgets">
        {{ $this->content }}
    </div>
</x-filament-panels::page>
