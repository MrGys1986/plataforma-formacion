<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Rúbrica institucional','description' => 'Una misma base de evaluación para todas las actividades y participantes.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Rúbrica institucional','description' => 'Una misma base de evaluación para todas las actividades y participantes.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <p class="font-semibold text-blue-900">Aplicación general</p>
        <p class="mt-1 text-sm leading-6 text-blue-800">Esta rúbrica se utiliza en todos los cursos. Así se mantienen criterios consistentes y no es necesario crear una evaluación diferente para cada actividad o participante.</p>
    </div>

    <div class="grid gap-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rubrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rubric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Todos los cursos y actividades</p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900"><?php echo e($rubric->name); ?></h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">De prueba</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Activa</span>
                </div>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-600"><?php echo e($rubric->description); ?></p>
            <div class="mt-5 grid gap-3 lg:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rubric->criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                    <div class="flex justify-between gap-4">
                        <p class="font-semibold text-slate-800"><?php echo e($criterion->name); ?></p>
                        <span class="font-bold text-blue-700"><?php echo e((float) $criterion->weight); ?>%</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e($criterion->description); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <div class="mt-5 flex justify-between gap-3 border-t border-slate-100 pt-4 text-sm text-slate-500">
                <span>Aprobación: <strong class="text-slate-700"><?php echo e((float) $rubric->passing_score); ?> puntos</strong></span>
                <span><?php echo e($rubric->criteria_count); ?> criterios</span>
            </div>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay una rúbrica institucional activa.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rubrics->hasPages()): ?><div class="mt-6"><?php echo e($rubrics->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\evaluator\rubrics\index.blade.php ENDPATH**/ ?>