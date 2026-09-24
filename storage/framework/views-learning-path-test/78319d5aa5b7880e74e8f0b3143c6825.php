<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value', 'detail' => null, 'tone' => 'blue']));

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

foreach (array_filter((['label', 'value', 'detail' => null, 'tone' => 'blue']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $bars = ['blue' => 'bg-blue-600', 'emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'rose' => 'bg-rose-500'];
    $percentage = max(0, min(100, (int) $value));
?>

<div>
    <div class="mb-2 flex items-end justify-between gap-4">
        <div><p class="text-sm font-semibold text-slate-800"><?php echo e($label); ?></p><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detail): ?><p class="mt-0.5 text-xs text-slate-500"><?php echo e($detail); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
        <span class="text-sm font-bold text-slate-900"><?php echo e($percentage); ?>%</span>
    </div>
    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full rounded-full <?php echo e($bars[$tone] ?? $bars['blue']); ?>" style="width: <?php echo e($percentage); ?>%"></div>
    </div>
</div>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\quality-progress.blade.php ENDPATH**/ ?>