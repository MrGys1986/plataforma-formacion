<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Catálogo de formación','description' => 'Cursos, minicursos y talleres disponibles para inscripción.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Catálogo de formación','description' => 'Cursos, minicursos y talleres disponibles para inscripción.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $courseName = Illuminate\Support\Str::lower($activity->name);
                    $courseCover = match (true) {
                        str_contains($courseName, 'liderazgo') => 'course-leadership.png',
                        str_contains($courseName, 'protección'),
                        str_contains($courseName, 'datos personales') => 'course-data-protection.png',
                        str_contains($courseName, 'diseño de experiencias') => 'course-learning-design.png',
                        str_contains($courseName, 'evaluación') => 'course-assessment.png',
                        str_contains($courseName, 'herramientas digitales') => 'course-digital-tools.png',
                        str_contains($courseName, 'inducción') => 'course-induction.png',
                        default => 'course-general.png',
                    };
                ?>
                <a class="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-950/10" href="<?php echo e(route('participant.catalog.show', $activity)); ?>">
                    <div class="relative aspect-[16/8] overflow-hidden bg-brand-50">
                        <img
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            src="<?php echo e($activity->coverFile?->optimizedImageUrl(960, 420) ?? $activity->trainingProgram?->coverFile?->optimizedImageUrl(960, 420) ?? asset('img/courses/'.$courseCover)); ?>"
                            alt="Imagen de referencia del curso <?php echo e($activity->name); ?>"
                            loading="lazy"
                            decoding="async"
                        >
                        <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-[0.68rem] font-extrabold uppercase tracking-[0.12em] text-brand-700 shadow-sm backdrop-blur"><?php echo e($activity->activityType?->name ?? 'Formación'); ?></span>
                    </div>
                    <div class="flex flex-1 flex-col p-5 text-center">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900"><?php echo e($activity->name); ?></h2>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500"><?php echo e($activity->description ?: 'Actividad formativa institucional.'); ?></p>
                        <div class="mt-auto pt-5 text-sm">
                            <div class="flex items-center justify-center gap-3 border-t border-slate-100 pt-4">
                                <span class="font-semibold text-slate-500"><?php echo e($activity->duration_hours); ?> horas</span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->requires_payment): ?>
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 font-bold text-amber-700">$<?php echo e(number_format((float) $activity->cost, 2)); ?> MXN</span>
                            <?php else: ?>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">Sin costo</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 font-bold text-white shadow-sm transition group-hover:bg-brand-700">Ver información <span aria-hidden="true">→</span></span>
                        </div>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-brand-50/60 px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-700 shadow-sm"><?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => 'catalog']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'catalog']); ?>
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
<?php endif; ?></span>
                    <p class="mt-4 font-semibold text-slate-700">No hay actividades disponibles para inscripción.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="mt-6"><?php echo e($activities->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\catalog\index.blade.php ENDPATH**/ ?>