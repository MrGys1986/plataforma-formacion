<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => $microcredential->name,'description' => 'Detalle de tu insignia digital institucional.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($microcredential->name),'description' => 'Detalle de tu insignia digital institucional.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
            <section class="rounded-2xl bg-gradient-to-br from-slate-950 via-blue-950 to-violet-950 p-8 text-center text-white shadow-xl">
                <?php if (isset($component)) { $__componentOriginal69e436818d90d0ad30cd7322cba1dc94 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69e436818d90d0ad30cd7322cba1dc94 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.course-badge-emblem','data' => ['badge' => $microcredential,'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('course-badge-emblem'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($microcredential),'size' => 'lg']); ?>
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
                <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-blue-200">Universidad</p>
                <p class="mt-2 font-semibold"><?php echo e($microcredential->name); ?></p>
                <p class="mt-3 text-xs text-slate-300">ID <?php echo e($microcredential->public_id); ?></p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Verificada · Emitida</span>
                <p class="mt-5 text-slate-700"><?php echo e($microcredential->description); ?></p>

                <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Titular</dt>
                        <dd class="mt-1 font-medium text-slate-900"><?php echo e($microcredential->user?->name); ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Fecha de emisión</dt>
                        <dd class="mt-1 font-medium text-slate-900"><?php echo e($microcredential->issued_at?->format('d/m/Y')); ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Habilidades</dt>
                        <dd class="mt-1 text-sm text-slate-700"><?php echo e($microcredential->skills ?: 'No especificadas'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Competencias</dt>
                        <dd class="mt-1 text-sm text-slate-700"><?php echo e($microcredential->competencies ?: 'No especificadas'); ?></dd>
                    </div>
                </dl>

                <a class="mt-7 inline-flex rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                   href="<?php echo e(route('public.badges.verify', $microcredential)); ?>" target="_blank">
                    Abrir verificación pública
                </a>
            </section>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\badges\show.blade.php ENDPATH**/ ?>