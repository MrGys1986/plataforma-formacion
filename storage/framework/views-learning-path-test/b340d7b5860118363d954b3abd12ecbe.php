<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href', 'active' => false, 'icon' => 'squares', 'variant' => 'surface']));

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

foreach (array_filter((['href', 'active' => false, 'icon' => 'squares', 'variant' => 'surface']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a
    href="<?php echo e($href); ?>"
    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'group/nav relative flex items-center gap-3 overflow-hidden rounded-xl border font-semibold transition duration-200',
        'min-h-12 px-3 py-2.5' => $variant === 'surface',
        'min-h-11 shrink-0 px-3 py-2 text-sm' => $variant === 'header',
        'border-brand-200 bg-gradient-to-r from-brand-100 to-brand-50 text-brand-800 shadow-sm ring-1 ring-brand-100' => $variant === 'surface' && $active,
        'border-transparent text-slate-600 hover:border-brand-100 hover:bg-brand-50 hover:text-brand-700' => $variant === 'surface' && ! $active,
        'border-white/20 bg-white text-brand-900 shadow-lg shadow-brand-950/20' => $variant === 'header' && $active,
        'border-transparent text-blue-100 hover:border-white/15 hover:bg-white/10 hover:text-white' => $variant === 'header' && ! $active,
    ]); ?>"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active && $variant === 'surface'): ?><span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-brand-600"></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'flex shrink-0 items-center justify-center rounded-lg transition',
        'h-9 w-9' => $variant === 'surface',
        'h-7 w-7' => $variant === 'header',
        'bg-brand-600 text-white shadow-md shadow-brand-600/20' => $variant === 'surface' && $active,
        'bg-slate-100 text-slate-500 group-hover/nav:bg-white group-hover/nav:text-brand-700' => $variant === 'surface' && ! $active,
        'bg-brand-50 text-brand-700' => $variant === 'header' && $active,
        'bg-white/10 text-blue-100 group-hover/nav:bg-white/15 group-hover/nav:text-white' => $variant === 'header' && ! $active,
    ]); ?>">
        <?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => $icon,'class' => \Illuminate\Support\Arr::toCssClasses(['h-5 w-5' => $variant === 'surface', 'h-4 w-4' => $variant === 'header'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses(['h-5 w-5' => $variant === 'surface', 'h-4 w-4' => $variant === 'header']))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $attributes = $__attributesOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__attributesOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $component = $__componentOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__componentOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
    </span>
    <span class="min-w-0 flex-1 truncate"><?php echo e($slot); ?></span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($variant === 'surface'): ?>
        <svg class="h-4 w-4 shrink-0 text-brand-500 opacity-0 transition group-hover/nav:translate-x-0.5 group-hover/nav:opacity-100 <?php echo e($active ? 'opacity-100' : ''); ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</a>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views/components/portal-nav-link.blade.php ENDPATH**/ ?>