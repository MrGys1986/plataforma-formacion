<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Constancias','description' => 'Documentos emitidos para participantes de educación continua.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Constancias','description' => 'Documentos emitidos para participantes de educación continua.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid gap-4 sm:grid-cols-3"><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Constancias','value' => $certificates->total()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Constancias','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates->total())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Emitidas','value' => $certificates->getCollection()->where('status','emitida')->count(),'detail' => 'En esta página','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Emitidas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates->getCollection()->where('status','emitida')->count()),'detail' => 'En esta página','tone' => 'emerald']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Descargadas','value' => $certificates->getCollection()->whereNotNull('downloaded_at')->count(),'detail' => 'En esta página']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Descargadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates->getCollection()->whereNotNull('downloaded_at')->count()),'detail' => 'En esta página']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?></div>
    <div class="mt-6 grid gap-4 lg:grid-cols-2"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><article class="rounded-xl border border-slate-200 p-5"><div class="flex items-start gap-4"><span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700">✓</span><div class="min-w-0"><p class="truncate font-semibold"><?php echo e($certificate->user?->name ?? 'Usuario no disponible'); ?></p><p class="mt-1 text-sm text-slate-500"><?php echo e($certificate->activity?->name ?? 'Sin actividad'); ?></p></div></div><dl class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-sm"><div><dt class="text-xs text-slate-500">Folio</dt><dd class="mt-1 font-medium"><?php echo e($certificate->folio ?? $certificate->public_id); ?></dd></div><div><dt class="text-xs text-slate-500">Emisión</dt><dd class="mt-1 font-medium"><?php echo e($certificate->issued_at?->format('d/m/Y') ?? 'Pendiente'); ?></dd></div></dl></article><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 lg:col-span-2">No hay constancias externas emitidas.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($certificates->hasPages()): ?><div class="mt-6"><?php echo e($certificates->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald467456a367f78493b5e3d963d2420fd)): ?>
<?php $attributes = $__attributesOriginald467456a367f78493b5e3d963d2420fd; ?>
<?php unset($__attributesOriginald467456a367f78493b5e3d963d2420fd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald467456a367f78493b5e3d963d2420fd)): ?>
<?php $component = $__componentOriginald467456a367f78493b5e3d963d2420fd; ?>
<?php unset($__componentOriginald467456a367f78493b5e3d963d2420fd); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\continuing-education\certificates\index.blade.php ENDPATH**/ ?>