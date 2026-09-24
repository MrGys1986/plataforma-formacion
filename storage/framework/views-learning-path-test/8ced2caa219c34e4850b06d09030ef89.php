<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Resultados de evaluación','description' => 'Dictámenes y calificaciones que has registrado como evaluador.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Resultados de evaluación','description' => 'Dictámenes y calificaciones que has registrado como evaluador.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="space-y-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="rounded-xl border border-slate-200 p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr($result->user?->name ?? '?', 0, 1))); ?></span>
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900"><?php echo e($result->user?->name ?? 'Usuario no disponible'); ?></p><p class="truncate text-sm text-slate-500"><?php echo e($result->evaluation?->name ?? 'Evaluación no disponible'); ?></p></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => $result->result === 'aprobado','bg-red-100 text-red-700' => $result->result === 'no_aprobado','bg-amber-100 text-amber-700' => $result->result === 'pendiente']); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $result->result))); ?></span>
                    <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-bold text-white"><?php echo e($result->score ?? '—'); ?> / 100</span>
                </div>
            </div>
            <div class="mt-4 border-l-2 border-blue-200 pl-4"><p class="text-sm font-semibold text-slate-700">Actividad</p><p class="mt-1 text-sm text-slate-600"><?php echo e($result->evaluation?->activity?->name ?? 'Sin actividad'); ?></p></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result->observations): ?><p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-600"><span class="font-semibold text-slate-800">Observaciones:</span> <?php echo e($result->observations); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 border-t border-slate-100 pt-4 text-sm text-slate-500"><span>Evaluado: <?php echo e($result->evaluated_at?->format('d/m/Y H:i') ?? 'Sin fecha'); ?></span><span>Puntaje mínimo: <?php echo e($result->evaluation?->minimum_score ?? 'No definido'); ?></span></div>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay resultados de evaluación asignados.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($results->hasPages()): ?><div class="mt-6"><?php echo e($results->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\evaluator\evaluations\index.blade.php ENDPATH**/ ?>