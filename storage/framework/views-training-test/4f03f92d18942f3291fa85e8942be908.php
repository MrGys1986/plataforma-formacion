<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Evidencias del área','description' => ''.e($selectedActivity ? 'Evidencias de '.$selectedActivity->name : 'Evidencias entregadas en actividades de tu área').': '.e($evidences->total()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Evidencias del área','description' => ''.e($selectedActivity ? 'Evidencias de '.$selectedActivity->name : 'Evidencias entregadas en actividades de tu área').': '.e($evidences->total()).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedActivity): ?>
            <div class="mb-5 flex flex-col gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-blue-800">Mostrando únicamente las evidencias de <span class="font-semibold"><?php echo e($selectedActivity->name); ?></span>.</p>
                <a class="text-sm font-semibold text-blue-700 hover:text-blue-900" href="<?php echo e(route('area-manager.evidences.index')); ?>">Ver todas</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                    <?php echo e(mb_strtoupper(mb_substr($evidence->user?->name ?? '?', 0, 1))); ?>

                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-900"><?php echo e($evidence->user?->name ?? 'Usuario no disponible'); ?></p>
                                    <p class="truncate text-sm text-slate-500"><?php echo e($evidence->user?->email ?? 'Sin correo'); ?></p>
                                </div>
                            </div>

                            <div class="mt-4 border-l-2 border-blue-200 pl-4">
                                <h2 class="font-semibold text-slate-900"><?php echo e($evidence->title); ?></h2>
                                <p class="mt-1 text-sm text-slate-600"><?php echo e($evidence->activity?->name ?? 'Actividad no disponible'); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidence->description): ?>
                                    <p class="mt-2 text-sm leading-6 text-slate-500"><?php echo e($evidence->description); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'w-fit shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-amber-100 text-amber-700' => $evidence->status === 'pendiente',
                            'bg-emerald-100 text-emerald-700' => $evidence->status === 'validada',
                            'bg-red-100 text-red-700' => $evidence->status === 'rechazada',
                            'bg-slate-100 text-slate-600' => ! in_array($evidence->status, ['pendiente', 'validada', 'rechazada'], true),
                        ]); ?>">
                            <?php echo e(ucfirst($evidence->status)); ?>

                        </span>
                    </div>

                    <dl class="mt-5 grid gap-3 border-t border-slate-100 pt-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <dt class="font-semibold text-slate-500">Tipo</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e(ucfirst(str_replace('_', ' ', $evidence->evidence_type))); ?></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Entrega</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e($evidence->created_at?->format('d/m/Y') ?? 'Sin fecha'); ?></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Evaluador</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e($evidence->assignedEvaluator?->name ?? 'Sin asignar'); ?></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Inscripción</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e(ucfirst($evidence->enrollment?->status ?? 'sin relación')); ?></dd>
                        </div>
                    </dl>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidence->status === 'rechazada' && $evidence->rejection_reason): ?>
                        <p class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700"><span class="font-semibold">Motivo:</span> <?php echo e($evidence->rejection_reason); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay evidencias registradas en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las entregas de participantes inscritos en actividades del área aparecerán aquí.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidences->hasPages()): ?>
            <div class="mt-6"><?php echo e($evidences->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\area-manager\evidences\index.blade.php ENDPATH**/ ?>