<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Cursos requeridos','description' => 'Catálogo de cursos y minicursos que debe realizar el personal de Recursos Humanos.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cursos requeridos','description' => 'Catálogo de cursos y minicursos que debe realizar el personal de Recursos Humanos.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <p class="font-semibold text-blue-900">Formación para Recursos Humanos</p>
        <p class="mt-1 text-sm leading-6 text-blue-800">Consulta la capacitación necesaria para el puesto. Las actividades disponibles y el avance del personal se actualizan desde este catálogo.</p>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="flex flex-col rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700"><?php echo e($program->activityType?->name ?? 'Curso'); ?></p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900"><?php echo e($program->name); ?></h2>
                </div>
                <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Requerido</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($program->description): ?><p class="mt-3 text-sm leading-6 text-slate-600"><?php echo e($program->description); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <dl class="mt-5 grid gap-4 rounded-lg bg-slate-50 p-4 text-sm sm:grid-cols-2">
                <div><dt class="font-semibold text-slate-500">Modalidad</dt><dd class="mt-1 text-slate-800"><?php echo e(ucfirst($program->default_modality)); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Duración</dt><dd class="mt-1 text-slate-800"><?php echo e((float) $program->duration_hours); ?> horas</dd></div>
                <div><dt class="font-semibold text-slate-500">Área</dt><dd class="mt-1 text-slate-800"><?php echo e($program->area?->name ?? 'Formación transversal'); ?></dd></div>
                <div><dt class="font-semibold text-slate-500">Constancia</dt><dd class="mt-1 text-slate-800"><?php echo e($program->generates_certificate ? 'Sí' : 'No'); ?></dd></div>
            </dl>

            <div class="mt-auto grid grid-cols-3 gap-3 border-t border-slate-100 pt-5 text-center">
                <div><p class="text-2xl font-bold text-slate-900"><?php echo e($program->editions_count); ?></p><p class="text-xs text-slate-500">Actividades</p></div>
                <div><p class="text-2xl font-bold text-blue-700"><?php echo e($program->enrollments_count); ?></p><p class="text-xs text-slate-500">Inscripciones</p></div>
                <div><p class="text-2xl font-bold text-emerald-700"><?php echo e($program->completed_enrollments_count); ?></p><p class="text-xs text-slate-500">Completados</p></div>
            </div>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No hay cursos requeridos para Recursos Humanos.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($programs->hasPages()): ?><div class="mt-6"><?php echo e($programs->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\rh\competencies\index.blade.php ENDPATH**/ ?>