<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Evidencias','description' => 'Seguimiento de entregas realizadas en actividades internas de capacitación.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Evidencias','description' => 'Seguimiento de entregas realizadas en actividades internas de capacitación.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <form class="mb-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_200px_auto]" method="GET" action="<?php echo e(route('rh.evidences.index')); ?>" data-auto-filter>
        <input class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm" type="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Buscar evidencia, participante o actividad">
        <select class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="status">
            <option value="">Todos los estados</option>
            <option value="pendiente" <?php if(request('status') === 'pendiente'): echo 'selected'; endif; ?>>Pendientes</option>
            <option value="validada" <?php if(request('status') === 'validada'): echo 'selected'; endif; ?>>Validadas</option>
            <option value="rechazada" <?php if(request('status') === 'rechazada'): echo 'selected'; endif; ?>>Rechazadas</option>
        </select>
        <?php if (isset($component)) { $__componentOriginal50597db1f45b00e1436c6f7332fec915 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50597db1f45b00e1436c6f7332fec915 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auto-filter-actions','data' => ['resetUrl' => route('rh.evidences.index'),'active' => request()->hasAny(['search','status'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auto-filter-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('rh.evidences.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search','status']))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50597db1f45b00e1436c6f7332fec915)): ?>
<?php $attributes = $__attributesOriginal50597db1f45b00e1436c6f7332fec915; ?>
<?php unset($__attributesOriginal50597db1f45b00e1436c6f7332fec915); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50597db1f45b00e1436c6f7332fec915)): ?>
<?php $component = $__componentOriginal50597db1f45b00e1436c6f7332fec915; ?>
<?php unset($__componentOriginal50597db1f45b00e1436c6f7332fec915); ?>
<?php endif; ?>
    </form>

    <p class="mb-4 text-sm text-slate-500"><span class="font-semibold text-slate-800"><?php echo e($evidences->total()); ?></span> evidencias encontradas</p>
    <div class="grid gap-5 xl:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr($evidence->user?->name ?? '?', 0, 1))); ?></span>
                    <div class="min-w-0"><p class="truncate font-semibold text-slate-900"><?php echo e($evidence->user?->name ?? 'Usuario no disponible'); ?></p><p class="truncate text-sm text-slate-500"><?php echo e($evidence->user?->area?->name ?? 'Sin área asignada'); ?></p></div>
                </div>
                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-amber-100 text-amber-700' => $evidence->status === 'pendiente','bg-emerald-100 text-emerald-700' => $evidence->status === 'validada','bg-red-100 text-red-700' => $evidence->status === 'rechazada']); ?>"><?php echo e(ucfirst($evidence->status)); ?></span>
            </div>
            <div class="mt-5 border-l-2 border-blue-200 pl-4"><h2 class="font-semibold text-slate-900"><?php echo e($evidence->title); ?></h2><p class="mt-1 text-sm text-slate-500"><?php echo e($evidence->activity?->name ?? 'Actividad no disponible'); ?></p><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidence->description): ?><p class="mt-2 text-sm leading-6 text-slate-600"><?php echo e($evidence->description); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
            <dl class="mt-5 grid gap-4 border-t border-slate-100 pt-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Tipo</dt><dd class="mt-1 text-slate-800"><?php echo e(ucfirst(str_replace('_',' ', $evidence->evidence_type))); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Entrega</dt><dd class="mt-1 text-slate-800"><?php echo e($evidence->created_at?->format('d/m/Y') ?? 'Sin fecha'); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Evaluador</dt><dd class="mt-1 text-slate-800"><?php echo e($evidence->assignedEvaluator?->name ?? 'Sin asignar'); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Inscripción</dt><dd class="mt-1 text-slate-800"><?php echo e(ucfirst($evidence->enrollment?->status ?? 'sin relación')); ?></dd></div>
            </dl>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No se encontraron evidencias con los filtros seleccionados.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidences->hasPages()): ?><div class="mt-6"><?php echo e($evidences->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\rh\evidences\index.blade.php ENDPATH**/ ?>