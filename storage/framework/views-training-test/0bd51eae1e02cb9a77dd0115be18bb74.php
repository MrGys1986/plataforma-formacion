<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Evidencias','description' => 'Expedientes académicos y avance de su proceso de validación.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Evidencias','description' => 'Expedientes académicos y avance de su proceso de validación.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php
            $pagePending = $evidences->where('status', 'pendiente')->count();
            $pageValidated = $evidences->where('status', 'validada')->count();
            $pageRejected = $evidences->where('status', 'rechazada')->count();
        ?>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Total de evidencias','value' => $evidences->count(),'detail' => 'Expedientes visibles']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total de evidencias','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences->count()),'detail' => 'Expedientes visibles']); ?>
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
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Pendientes','value' => $pagePending,'detail' => 'En esta página','tone' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pendientes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pagePending),'detail' => 'En esta página','tone' => 'amber']); ?>
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
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Validadas','value' => $pageValidated,'detail' => 'En esta página','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Validadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageValidated),'detail' => 'En esta página','tone' => 'emerald']); ?>
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
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Con observaciones','value' => $pageRejected,'detail' => 'En esta página','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Con observaciones','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageRejected),'detail' => 'En esta página','tone' => 'rose']); ?>
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
<?php endif; ?>
        </div>

        <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Evidencia</th><th class="px-5 py-3">Participante</th><th class="px-5 py-3">Actividad</th><th class="px-5 py-3">Fecha</th><th class="px-5 py-3">Estado</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-slate-50"><td class="px-5 py-4"><p class="font-semibold text-slate-900"><?php echo e($evidence->title); ?></p><p class="mt-1 text-xs text-slate-500"><?php echo e(ucfirst(str_replace('_', ' ', $evidence->evidence_type ?? 'documento'))); ?></p></td><td class="px-5 py-4"><p class="font-medium text-slate-800"><?php echo e($evidence->user?->name ?? 'Usuario no disponible'); ?></p><p class="text-xs text-slate-500"><?php echo e($evidence->user?->email); ?></p></td><td class="px-5 py-4 text-slate-600"><?php echo e($evidence->activity?->name ?? 'Sin actividad'); ?></td><td class="whitespace-nowrap px-5 py-4 text-slate-600"><?php echo e($evidence->created_at?->format('d/m/Y') ?? 'Sin fecha'); ?></td><td class="px-5 py-4"><span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['rounded-full px-2.5 py-1 text-xs font-semibold','bg-amber-100 text-amber-700' => $evidence->status === 'pendiente','bg-emerald-100 text-emerald-700' => $evidence->status === 'validada','bg-rose-100 text-rose-700' => $evidence->status === 'rechazada','bg-slate-100 text-slate-600' => !in_array($evidence->status, ['pendiente','validada','rechazada'], true)]); ?>"><?php echo e(ucfirst($evidence->status ?? 'Sin estado')); ?></span></td></tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td class="px-6 py-12 text-center text-slate-500" colspan="5"><p class="font-semibold text-slate-700">No hay evidencias registradas.</p><p class="mt-1 text-sm">Las entregas aparecerán aquí cuando los participantes las envíen.</p></td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\quality\evidences\index.blade.php ENDPATH**/ ?>