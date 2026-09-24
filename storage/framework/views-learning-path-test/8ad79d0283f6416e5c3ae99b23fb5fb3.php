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
    $tones = [
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-100',
        'slate' => 'bg-slate-50 text-slate-700 ring-slate-200',
    ];
?>

<article class="flex min-h-36 flex-col items-center justify-center rounded-2xl p-5 text-center shadow-sm ring-1 transition duration-200 hover:-translate-y-0.5 hover:shadow-md <?php echo e($tones[$tone] ?? $tones['blue']); ?>">
    <p class="text-sm font-semibold opacity-80"><?php echo e($label); ?></p>
    <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900"><?php echo e($value); ?></p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detail): ?><p class="mt-2 text-xs font-medium opacity-70"><?php echo e($detail); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</article>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\quality-stat.blade.php ENDPATH**/ ?>