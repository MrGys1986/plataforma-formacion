<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['resetUrl', 'active' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['resetUrl', 'active' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex min-h-11 items-center justify-end gap-3" data-auto-filter-actions>
    <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-500" data-auto-filter-status aria-live="polite">
        <span class="h-2 w-2 rounded-full bg-emerald-500" data-auto-filter-indicator></span>
        <span data-auto-filter-label>Filtros automáticos</span>
    </span>

    <a class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'min-h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-slate-600 hover:border-brand-300 hover:text-brand-700',
        'inline-flex' => $active,
        'hidden' => ! $active,
    ]); ?>" href="<?php echo e($resetUrl); ?>" data-auto-filter-reset>Limpiar</a>

    <noscript>
        <button class="inline-flex min-h-10 items-center justify-center rounded-xl bg-brand-700 px-4 text-sm font-bold text-white" type="submit">Aplicar</button>
    </noscript>
</div>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\auto-filter-actions.blade.php ENDPATH**/ ?>