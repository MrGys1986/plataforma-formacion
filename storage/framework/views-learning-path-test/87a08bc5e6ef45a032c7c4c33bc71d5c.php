<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description' => null]));

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

foreach (array_filter((['title', 'description' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section class="portal-surface overflow-hidden rounded-3xl border border-brand-100 bg-white p-4 shadow-xl shadow-brand-950/5 sm:p-6 lg:p-8">
    <div class="relative mb-7 overflow-hidden rounded-2xl border border-brand-100 bg-gradient-to-r from-brand-50 to-white px-5 py-5 sm:px-6">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-brand-600"></div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-600">Plataforma institucional</p>
        <h1 class="mt-1.5 text-2xl font-extrabold tracking-tight text-brand-950 sm:text-3xl"><?php echo e($title); ?></h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600"><?php echo e($description); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo e($slot); ?>

</section>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\portal-page.blade.php ENDPATH**/ ?>