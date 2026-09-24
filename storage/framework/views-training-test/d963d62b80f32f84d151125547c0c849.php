<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items' => [], 'variant' => 'surface']));

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

foreach (array_filter((['items' => [], 'variant' => 'surface']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($item['type'] ?? 'link') === 'section'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($variant === 'surface'): ?>
            <div class="px-2 pb-1 pt-4 first:pt-1">
                <p class="border-t border-slate-100 pt-4 text-[0.66rem] font-extrabold uppercase tracking-[0.18em] text-slate-400"><?php echo e($item['label']); ?></p>
            </div>
        <?php else: ?>
            <span class="mx-1 h-6 w-px shrink-0 bg-white/15" aria-hidden="true"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php else: ?>
        <?php
            $href = route($item['route'], $item['parameters'] ?? []);
            $active = array_key_exists('section', $item)
                ? request()->routeIs('dashboard') && request()->query('section') === $item['section']
                : request()->routeIs(...$item['active']);
        ?>
        <?php if (isset($component)) { $__componentOriginaledb14122aca307aa5479143ea8dce323 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaledb14122aca307aa5479143ea8dce323 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-link','data' => ['href' => $href,'active' => $active,'icon' => $item['icon'],'variant' => $variant]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($href),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($active),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variant)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php echo e($item['label']); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaledb14122aca307aa5479143ea8dce323)): ?>
<?php $attributes = $__attributesOriginaledb14122aca307aa5479143ea8dce323; ?>
<?php unset($__attributesOriginaledb14122aca307aa5479143ea8dce323); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaledb14122aca307aa5479143ea8dce323)): ?>
<?php $component = $__componentOriginaledb14122aca307aa5479143ea8dce323; ?>
<?php unset($__componentOriginaledb14122aca307aa5479143ea8dce323); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\portal-navigation.blade.php ENDPATH**/ ?>