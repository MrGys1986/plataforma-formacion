<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Capacitación','description' => 'Oferta interna de formación y seguimiento de participación institucional.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Capacitación','description' => 'Oferta interna de formación y seguimiento de participación institucional.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-slate-900 p-5 text-white"><p class="text-sm text-slate-300">Actividades</p><p class="mt-2 text-3xl font-bold"><?php echo e($activities->total()); ?></p></div>
        <div class="rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Inscripciones en esta página</p><p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($activities->sum('enrollments_count')); ?></p></div>
        <div class="rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Evidencias en esta página</p><p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($activities->sum('evidences_count')); ?></p></div>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700"><?php echo e($activity->activityType?->name ?? 'Actividad'); ?></p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900"><?php echo e($activity->name); ?></h2>
                </div>
                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => in_array($activity->status, ['publicado','en_inscripcion','en_curso','finalizado'], true),'bg-amber-100 text-amber-700' => $activity->status === 'borrador','bg-red-100 text-red-700' => $activity->status === 'cancelado','bg-slate-100 text-slate-600' => !in_array($activity->status, ['publicado','en_inscripcion','en_curso','finalizado','borrador','cancelado'], true)]); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $activity->status))); ?></span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->description): ?><p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600"><?php echo e($activity->description); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Área</dt><dd class="mt-1 text-slate-800"><?php echo e($activity->area?->name ?? 'Sin área'); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Instructor</dt><dd class="mt-1 text-slate-800"><?php echo e($activity->instructor?->name ?? 'Sin asignar'); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Modalidad</dt><dd class="mt-1 text-slate-800"><?php echo e(ucfirst($activity->modality)); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Periodo</dt><dd class="mt-1 text-slate-800"><?php echo e($activity->start_date?->format('d/m/Y') ?? 'Por definir'); ?> – <?php echo e($activity->end_date?->format('d/m/Y') ?? 'Por definir'); ?></dd></div>
            </dl>

            <div class="mt-auto grid grid-cols-3 gap-3 pt-5 text-center text-sm">
                <div class="rounded-lg bg-blue-50 p-3"><p class="text-xl font-bold text-blue-700"><?php echo e($activity->enrollments_count); ?></p><p class="mt-1 text-xs text-blue-600">Inscripciones</p></div>
                <div class="rounded-lg bg-amber-50 p-3"><p class="text-xl font-bold text-amber-700"><?php echo e($activity->evidences_count); ?></p><p class="mt-1 text-xs text-amber-600">Evidencias</p></div>
                <div class="rounded-lg bg-emerald-50 p-3"><p class="text-xl font-bold text-emerald-700"><?php echo e($activity->certificates_count); ?></p><p class="mt-1 text-xs text-emerald-600">Constancias</p></div>
            </div>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No hay actividades internas registradas.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activities->hasPages()): ?><div class="mt-6"><?php echo e($activities->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\rh\training\index.blade.php ENDPATH**/ ?>