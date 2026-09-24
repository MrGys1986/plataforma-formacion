<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Mis insignias','description' => 'Reconocimientos digitales obtenidos por tus competencias y logros.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mis insignias','description' => 'Reconocimientos digitales obtenidos por tus competencias y logros.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $courseIdentity = $badge->learningPath?->public_id ?? $badge->learningPath?->slug ?? $badge->learningPath?->id ?? $badge->name;
                    $courseColors = ['#7c3aed', '#0891b2', '#ea580c', '#059669', '#db2777', '#ca8a04', '#4f46e5', '#be123c'];
                    $courseColor = $courseColors[abs(crc32((string) $courseIdentity)) % count($courseColors)];
                ?>
                <a class="group rounded-2xl border border-slate-200 bg-white p-6 text-center transition hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg"
                   href="<?php echo e(route('participant.badges.show', $badge)); ?>" style="border-top: 4px solid <?php echo e($courseColor); ?>;">
                    <?php if (isset($component)) { $__componentOriginal69e436818d90d0ad30cd7322cba1dc94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69e436818d90d0ad30cd7322cba1dc94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.course-badge-emblem','data' => ['badge' => $badge]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('course-badge-emblem'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($badge)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69e436818d90d0ad30cd7322cba1dc94)): ?>
<?php $attributes = $__attributesOriginal69e436818d90d0ad30cd7322cba1dc94; ?>
<?php unset($__attributesOriginal69e436818d90d0ad30cd7322cba1dc94); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69e436818d90d0ad30cd7322cba1dc94)): ?>
<?php $component = $__componentOriginal69e436818d90d0ad30cd7322cba1dc94; ?>
<?php unset($__componentOriginal69e436818d90d0ad30cd7322cba1dc94); ?>
<?php endif; ?>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em]" style="color: <?php echo e($courseColor); ?>;">Ruta completada</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900"><?php echo e($badge->name); ?></h2>
                    <p class="mt-2 text-sm text-slate-500"><?php echo e($badge->learningPath?->name ?? 'Ruta de aprendizaje'); ?></p>
                    <p class="mt-4 text-xs text-slate-400">Emitida <?php echo e($badge->issued_at?->format('d/m/Y')); ?></p>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                    Todavía no tienes insignias emitidas.
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="mt-6"><?php echo e($badges->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\badges\index.blade.php ENDPATH**/ ?>