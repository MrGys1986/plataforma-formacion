@props(['resetUrl', 'active' => false])

<div class="flex min-h-11 items-center justify-end gap-3" data-auto-filter-actions>
    <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-500" data-auto-filter-status aria-live="polite">
        <span class="h-2 w-2 rounded-full bg-emerald-500" data-auto-filter-indicator></span>
        <span data-auto-filter-label>Filtros automáticos</span>
    </span>

    <a @class([
        'min-h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-slate-600 hover:border-brand-300 hover:text-brand-700',
        'inline-flex' => $active,
        'hidden' => ! $active,
    ]) href="{{ $resetUrl }}" data-auto-filter-reset>Limpiar</a>

    <noscript>
        <button class="inline-flex min-h-10 items-center justify-center rounded-xl bg-brand-700 px-4 text-sm font-bold text-white" type="submit">Aplicar</button>
    </noscript>
</div>
