<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Actividades del área','description' => 'Actividades registradas y vinculadas con tu área: '.e($activities->total()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Actividades del área','description' => 'Actividades registradas y vinculadas con tu área: '.e($activities->total()).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-5 xl:grid-cols-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-600">
                                <?php echo e($activity->activityType?->name ?? 'Actividad'); ?>

                            </p>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900"><?php echo e($activity->name); ?></h2>
                        </div>
                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'w-fit shrink-0 rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-700' => in_array($activity->status, ['publicado', 'en_inscripcion', 'en_curso'], true),
                            'bg-blue-100 text-blue-700' => $activity->status === 'finalizado',
                            'bg-red-100 text-red-700' => $activity->status === 'cancelado',
                            'bg-slate-100 text-slate-600' => ! in_array($activity->status, ['publicado', 'en_inscripcion', 'en_curso', 'finalizado', 'cancelado'], true),
                        ]); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $activity->status))); ?>

                        </span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->description): ?>
                        <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600"><?php echo e($activity->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="font-semibold text-slate-500">Instructor</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e($activity->instructor?->name ?? 'Sin instructor asignado'); ?></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Modalidad</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e(ucfirst($activity->modality)); ?></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Periodo</dt>
                            <dd class="mt-1 text-slate-800">
                                <?php echo e($activity->start_date?->format('d/m/Y') ?? 'Por definir'); ?>

                                <span aria-hidden="true">–</span>
                                <?php echo e($activity->end_date?->format('d/m/Y') ?? 'Por definir'); ?>

                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Duración</dt>
                            <dd class="mt-1 text-slate-800"><?php echo e(rtrim(rtrim(number_format((float) $activity->duration_hours, 2), '0'), '.')); ?> horas</dd>
                        </div>
                    </dl>

                    <div class="mt-auto flex flex-col gap-3 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo e($activity->enrollments_count); ?> inscripciones</p>
                            <p class="mt-0.5 text-xs text-slate-500">
                                Cupo: <?php echo e($activity->max_capacity ?? 'sin límite definido'); ?>

                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a
                                class="inline-flex items-center justify-center rounded-lg border border-blue-200 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-50"
                                href="<?php echo e(route('area-manager.evidences.index', ['activity' => $activity->id])); ?>"
                            >
                                Ver evidencias
                            </a>
                            <a
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                href="<?php echo e(route('area-manager.enrollments.index', ['activity' => $activity->id])); ?>"
                            >
                                Ver inscripciones
                            </a>
                        </div>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center xl:col-span-2">
                    <p class="font-semibold text-slate-700">No hay actividades registradas en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Las actividades creadas y asignadas a esta área aparecerán aquí.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activities->hasPages()): ?>
            <div class="mt-6"><?php echo e($activities->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\area-manager\activities\index.blade.php ENDPATH**/ ?>