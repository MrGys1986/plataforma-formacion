<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Mi formación','description' => 'Cursos en los que participas, avance y evidencias propias.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mi formación','description' => 'Cursos en los que participas, avance y evidencias propias.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a class="group flex min-h-56 flex-col items-center rounded-2xl border border-brand-100 bg-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl hover:shadow-brand-950/10" href="<?php echo e(route('participant.learning.show', $enrollment)); ?>">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white"><?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => 'courses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'courses']); ?>
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
                    <p class="mt-4 text-lg font-extrabold tracking-tight text-slate-900"><?php echo e($enrollment->activity?->name); ?></p>
                    <div class="mt-3 flex flex-wrap justify-center gap-2 text-xs">
                        <span class="rounded-full bg-brand-50 px-3 py-1 font-bold text-brand-700"><?php echo e(ucfirst($enrollment->status)); ?></span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-600"><?php echo e(ucfirst(str_replace('_', ' ', $enrollment->completion_status))); ?></span>
                    </div>
                    <span class="mt-auto pt-5 text-sm font-bold text-brand-700">Continuar curso →</span>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="col-span-full rounded-2xl border border-dashed border-brand-200 bg-brand-50/60 px-6 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-700 shadow-sm"><?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => 'courses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'courses']); ?>
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
                    <p class="mt-4 font-semibold text-slate-700">Aún no tienes inscripciones.</p>
                    <a class="mt-3 inline-flex rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-700" href="<?php echo e(route('participant.catalog.index')); ?>">Explorar cursos</a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php echo e($enrollments->links()); ?>

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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views/participant/my-courses/index.blade.php ENDPATH**/ ?>