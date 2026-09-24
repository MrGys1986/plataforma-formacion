<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Inscripciones del área','description' => ''.e($selectedActivity ? 'Inscripciones de '.$selectedActivity->name : 'Inscripciones vinculadas a actividades de tu área').': '.e($enrollments->total()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Inscripciones del área','description' => ''.e($selectedActivity ? 'Inscripciones de '.$selectedActivity->name : 'Inscripciones vinculadas a actividades de tu área').': '.e($enrollments->total()).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedActivity): ?>
            <div class="mb-5 flex flex-col gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-blue-800">Mostrando únicamente las inscripciones de <span class="font-semibold"><?php echo e($selectedActivity->name); ?></span>.</p>
                <a class="text-sm font-semibold text-blue-700 hover:text-blue-900" href="<?php echo e(route('area-manager.enrollments.index')); ?>">Ver todas</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                    <?php echo e(mb_strtoupper(mb_substr($enrollment->user?->name ?? '?', 0, 1))); ?>

                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900"><?php echo e($enrollment->user?->name ?? 'Usuario no disponible'); ?></p>
                                    <p class="truncate text-sm text-slate-500"><?php echo e($enrollment->user?->email ?? 'Sin correo'); ?></p>
                                </div>
                            </div>

                            <div class="mt-4 border-l-2 border-blue-200 pl-4">
                                <p class="font-semibold text-slate-800"><?php echo e($enrollment->activity?->name ?? 'Actividad no disponible'); ?></p>
                                <p class="mt-1 text-sm text-slate-500">
                                    <?php echo e($enrollment->activity?->activityType?->name ?? 'Actividad'); ?>

                                    <span aria-hidden="true">·</span>
                                    <?php echo e(ucfirst($enrollment->activity?->modality ?? 'sin modalidad')); ?>

                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 lg:max-w-sm lg:justify-end">
                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $enrollment->status === 'aprobada',
                                'bg-amber-100 text-amber-700' => $enrollment->status === 'solicitada',
                                'bg-red-100 text-red-700' => $enrollment->status === 'rechazada',
                                'bg-slate-100 text-slate-600' => ! in_array($enrollment->status, ['aprobada', 'solicitada', 'rechazada'], true),
                            ]); ?>">
                                Inscripción: <?php echo e(ucfirst($enrollment->status)); ?>

                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                Avance: <?php echo e(ucfirst(str_replace('_', ' ', $enrollment->completion_status))); ?>

                            </span>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-2 border-t border-slate-100 pt-4 text-sm text-slate-500 sm:grid-cols-2 lg:grid-cols-3">
                        <p><span class="font-semibold text-slate-600">Solicitud:</span> <?php echo e($enrollment->requested_at?->format('d/m/Y') ?? 'Sin fecha'); ?></p>
                        <p><span class="font-semibold text-slate-600">Área:</span> <?php echo e($enrollment->activity?->area?->name ?? $enrollment->user?->area?->name ?? 'Sin área'); ?></p>
                        <p><span class="font-semibold text-slate-600">Calificación:</span> <?php echo e($enrollment->final_score ?? 'Pendiente'); ?></p>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay inscripciones en las actividades de tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las inscripciones aparecerán aquí cuando un participante se vincule con una actividad del área.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollments->hasPages()): ?>
            <div class="mt-6"><?php echo e($enrollments->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\area-manager\enrollments\index.blade.php ENDPATH**/ ?>