<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Reportes del área','description' => 'Resumen actualizado de formación, participación y evidencias de tu área.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reportes del área','description' => 'Resumen actualizado de formación, participación y evidencias de tu área.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php
            $approvalRate = $training['total'] > 0
                ? round(($training['aprobadas'] / $training['total']) * 100)
                : 0;
            $validationRate = $evidences['total'] > 0
                ? round(($evidences['validadas'] / $evidences['total']) * 100)
                : 0;
        ?>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="<?php echo e(route('area-manager.activities.index')); ?>">
                <p class="text-sm font-semibold text-slate-500">Actividades</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($activities->count()); ?></p>
                <p class="mt-2 text-xs font-semibold text-blue-700">Consultar actividades →</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="<?php echo e(route('area-manager.participants.index')); ?>">
                <p class="text-sm font-semibold text-slate-500">Usuarios del área</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($participantsCount); ?></p>
                <p class="mt-2 text-xs font-semibold text-blue-700">Consultar participantes →</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="<?php echo e(route('area-manager.enrollments.index')); ?>">
                <p class="text-sm font-semibold text-slate-500">Inscripciones</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($training['total']); ?></p>
                <p class="mt-2 text-xs font-semibold text-blue-700"><?php echo e($approvalRate); ?>% aprobadas</p>
            </a>
            <a class="rounded-xl border border-slate-200 p-5 transition hover:border-blue-200 hover:shadow-sm" href="<?php echo e(route('area-manager.evidences.index')); ?>">
                <p class="text-sm font-semibold text-slate-500">Evidencias</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($evidences['total']); ?></p>
                <p class="mt-2 text-xs font-semibold text-blue-700"><?php echo e($validationRate); ?>% validadas</p>
            </a>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Estado de inscripciones</h2>
                    <span class="text-sm text-slate-500">Total: <?php echo e($training['total']); ?></span>
                </div>
                <div class="mt-5 space-y-4 text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                        ['label' => 'Aprobadas', 'value' => $training['aprobadas'], 'color' => 'bg-emerald-500'],
                        ['label' => 'Solicitadas', 'value' => $training['solicitadas'], 'color' => 'bg-amber-500'],
                        ['label' => 'Rechazadas', 'value' => $training['rechazadas'], 'color' => 'bg-red-500'],
                        ['label' => 'Completadas', 'value' => $training['completadas'], 'color' => 'bg-blue-500'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php ($percentage = $training['total'] > 0 ? round(($item['value'] / $training['total']) * 100) : 0); ?>
                        <div>
                            <div class="mb-1.5 flex items-center justify-between gap-3">
                                <span class="font-medium text-slate-600"><?php echo e($item['label']); ?></span>
                                <span class="font-semibold text-slate-900"><?php echo e($item['value']); ?> <span class="font-normal text-slate-400">(<?php echo e($percentage); ?>%)</span></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full <?php echo e($item['color']); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 p-5">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-slate-900">Estado de evidencias</h2>
                    <span class="text-sm text-slate-500">Total: <?php echo e($evidences['total']); ?></span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                        ['label' => 'Pendientes', 'value' => $evidences['pendientes'], 'classes' => 'bg-amber-50 text-amber-700'],
                        ['label' => 'Validadas', 'value' => $evidences['validadas'], 'classes' => 'bg-emerald-50 text-emerald-700'],
                        ['label' => 'Rechazadas', 'value' => $evidences['rechazadas'], 'classes' => 'bg-red-50 text-red-700'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="rounded-xl p-4 text-center <?php echo e($item['classes']); ?>">
                            <p class="text-2xl font-bold"><?php echo e($item['value']); ?></p>
                            <p class="mt-1 text-xs font-semibold"><?php echo e($item['label']); ?></p>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <div class="mt-5 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <span class="font-semibold text-slate-800">Seguimiento:</span>
                    <?php echo e($evidences['pendientes']); ?> <?php echo e($evidences['pendientes'] === 1 ? 'evidencia requiere' : 'evidencias requieren'); ?> revisión.
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-xl border border-slate-200">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Detalle por actividad</h2>
                <p class="mt-1 text-sm text-slate-500">Comparativo de inscripciones y evidencias registradas.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-white text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Actividad</th>
                            <th class="px-5 py-3 text-center font-semibold">Inscripciones</th>
                            <th class="px-5 py-3 text-center font-semibold">Aprobadas</th>
                            <th class="px-5 py-3 text-center font-semibold">Solicitadas</th>
                            <th class="px-5 py-3 text-center font-semibold">Evidencias</th>
                            <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900"><?php echo e($activity->name); ?></p>
                                    <p class="mt-1 text-xs text-slate-500"><?php echo e($activity->activityType?->name ?? 'Actividad'); ?></p>
                                </td>
                                <td class="px-5 py-4 text-center font-semibold text-slate-700"><?php echo e($activity->enrollments_count); ?></td>
                                <td class="px-5 py-4 text-center text-emerald-700"><?php echo e($activity->approved_enrollments_count); ?></td>
                                <td class="px-5 py-4 text-center text-amber-700"><?php echo e($activity->requested_enrollments_count); ?></td>
                                <td class="px-5 py-4 text-center text-slate-700">
                                    <?php echo e($activity->evidences_count); ?>

                                    <span class="block text-xs text-slate-400"><?php echo e($activity->validated_evidences_count); ?> validadas</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a class="font-semibold text-blue-700 hover:text-blue-900" href="<?php echo e(route('area-manager.enrollments.index', ['activity' => $activity->id])); ?>">Inscripciones</a>
                                        <a class="font-semibold text-blue-700 hover:text-blue-900" href="<?php echo e(route('area-manager.evidences.index', ['activity' => $activity->id])); ?>">Evidencias</a>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr><td class="px-5 py-10 text-center text-slate-500" colspan="6">No hay actividades para generar el reporte.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\area-manager\reports\index.blade.php ENDPATH**/ ?>