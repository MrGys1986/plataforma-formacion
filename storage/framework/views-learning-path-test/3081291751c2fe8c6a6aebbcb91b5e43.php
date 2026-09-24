<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Asistencia de '.$activity->name,'description' => 'Consulta el historial de asistencia de cada participante inscrito.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Asistencia de '.$activity->name),'description' => 'Consulta el historial de asistencia de cada participante inscrito.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php echo $__env->make('personal.courses.partials.management-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attendanceRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $sessions = $enrollment->attendanceRecords;
                    $attended = $sessions->where('attended', true)->count();
                    $attendanceRate = $sessions->count() > 0 ? round(($attended / $sessions->count()) * 100) : 0;
                ?>
                <article class="rounded-xl border border-slate-200 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr($enrollment->user?->name ?? '?', 0, 1))); ?></span>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900"><?php echo e($enrollment->user?->name ?? 'Usuario no disponible'); ?></p>
                                <p class="truncate text-sm text-slate-500"><?php echo e($enrollment->user?->email ?? 'Sin correo'); ?></p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><?php echo e($sessions->count()); ?> sesiones</span>
                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $attendanceRate >= 80,
                                'bg-amber-100 text-amber-700' => $attendanceRate > 0 && $attendanceRate < 80,
                                'bg-slate-100 text-slate-600' => $attendanceRate === 0,
                            ]); ?>"><?php echo e($attendanceRate); ?>% asistencia</span>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sessions->isNotEmpty()): ?>
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sessions->sortByDesc('session_date')->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'rounded-lg border px-3 py-2 text-xs font-semibold',
                                    'border-emerald-200 bg-emerald-50 text-emerald-700' => $record->attended,
                                    'border-red-200 bg-red-50 text-red-700' => ! $record->attended,
                                ]); ?>">
                                    <?php echo e($record->session_date?->format('d/m/Y')); ?> · <?php echo e($record->attended ? 'Presente' : 'Ausente'); ?>

                                </span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-500">Aún no hay sesiones de asistencia registradas para este participante.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">No hay participantes inscritos en esta actividad.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendanceRecords->hasPages()): ?><div class="mt-6"><?php echo e($attendanceRecords->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\personal\attendance\index.blade.php ENDPATH**/ ?>