<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => $activity->name,'description' => 'Seguimiento, evaluación y constancias de los participantes inscritos.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activity->name),'description' => 'Seguimiento, evaluación y constancias de los participantes inscritos.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><?php echo e(session('status')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Revisa la información indicada:</p>
                <ul class="mt-1 list-disc pl-5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><li><?php echo e($error); ?></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-end" method="GET" data-auto-filter>
            <label class="sr-only" for="student-search">Buscar alumno</label>
            <input class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500" id="student-search" name="q" value="<?php echo e($search); ?>" placeholder="Buscar alumno por nombre o correo…">
            <?php if (isset($component)) { $__componentOriginal50597db1f45b00e1436c6f7332fec915 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50597db1f45b00e1436c6f7332fec915 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auto-filter-actions','data' => ['resetUrl' => route('participant.professor.teaching.show', $activity),'active' => $search !== '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auto-filter-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('participant.professor.teaching.show', $activity)),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($search !== '')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50597db1f45b00e1436c6f7332fec915)): ?>
<?php $attributes = $__attributesOriginal50597db1f45b00e1436c6f7332fec915; ?>
<?php unset($__attributesOriginal50597db1f45b00e1436c6f7332fec915); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50597db1f45b00e1436c6f7332fec915)): ?>
<?php $component = $__componentOriginal50597db1f45b00e1436c6f7332fec915; ?>
<?php unset($__componentOriginal50597db1f45b00e1436c6f7332fec915); ?>
<?php endif; ?>
        </form>

        <section class="mb-8">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Solicitudes de inscripción</h2>
                    <p class="mt-1 text-sm text-slate-500">Aprueba o rechaza a las personas que desean ingresar al curso.</p>
                </div>
                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'rounded-full px-3 py-1 text-xs font-semibold',
                    'bg-amber-100 text-amber-700' => $pendingEnrollments->isNotEmpty(),
                    'bg-slate-100 text-slate-600' => $pendingEnrollments->isEmpty(),
                ]); ?>"><?php echo e($pendingEnrollments->count()); ?> pendientes</span>
            </div>

            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendingEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <article class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <h3 class="font-semibold text-slate-950"><?php echo e($enrollment->user?->name); ?></h3>
                                <p class="mt-1 text-sm text-slate-500"><?php echo e($enrollment->user?->email); ?></p>
                                <p class="mt-2 text-xs font-medium text-amber-700">Solicitud recibida <?php echo e($enrollment->requested_at?->format('d/m/Y H:i') ?? $enrollment->created_at?->format('d/m/Y H:i')); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->requires_payment && in_array($enrollment->payment_status, ['validado', 'pagado'], true)): ?>
                                    <p class="mt-2 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Pago y comprobante validados</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                                <form method="POST" action="<?php echo e(route('participant.professor.teaching.enrollments.review', [$activity, $enrollment])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="aprobada">
                                    <button class="w-full rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500" type="submit">Aprobar inscripción</button>
                                </form>

                                <form class="flex flex-col gap-2 sm:flex-row" method="POST" action="<?php echo e(route('participant.professor.teaching.enrollments.review', [$activity, $enrollment])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="rechazada">
                                    <label class="sr-only" for="rejection-<?php echo e($enrollment->id); ?>">Motivo del rechazo</label>
                                    <input class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" id="rejection-<?php echo e($enrollment->id); ?>" name="reason" maxlength="1000" placeholder="Motivo del rechazo" required>
                                    <button class="rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500" type="submit">Rechazar</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <p class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">No hay solicitudes pendientes<?php echo e($search !== '' ? ' para esta búsqueda' : ''); ?>.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>

        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-slate-900">Participantes inscritos</h2>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700"><?php echo e($enrollments->count()); ?> resultados</span>
        </div>

        <div class="space-y-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php ($certificate = $enrollment->certificates->first()); ?>
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-slate-950"><?php echo e($enrollment->user?->name); ?></h3>
                            <p class="mt-1 text-sm text-slate-500"><?php echo e($enrollment->user?->email); ?></p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700"><?php echo e(ucfirst($enrollment->status)); ?></span>
                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'rounded-full px-3 py-1',
                                'bg-emerald-100 text-emerald-700' => $enrollment->completion_status === 'completado',
                                'bg-amber-100 text-amber-700' => $enrollment->completion_status !== 'completado',
                            ]); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $enrollment->completion_status))); ?></span>
                        </div>
                    </header>

                    <div class="grid gap-6 p-5 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,.65fr)]">
                        <section>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <h4 class="font-semibold text-slate-900">Evidencias del alumno</h4>
                                <span class="text-xs font-medium text-slate-500"><?php echo e($enrollment->evidences->count()); ?> archivos</span>
                            </div>

                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $enrollment->evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <p class="font-medium text-slate-900"><?php echo e($evidence->title); ?></p>
                                                <p class="mt-1 text-xs text-slate-500"><?php echo e(ucfirst($evidence->evidence_type)); ?></p>
                                            </div>
                                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'rounded-full px-3 py-1 text-xs font-semibold',
                                                'bg-emerald-100 text-emerald-700' => $evidence->status === 'validada',
                                                'bg-rose-100 text-rose-700' => $evidence->status === 'rechazada',
                                                'bg-amber-100 text-amber-700' => $evidence->status === 'pendiente',
                                            ]); ?>"><?php echo e(ucfirst($evidence->status)); ?></span>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidence->fileUpload): ?>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($evidence->fileUpload->mime_type, ['application/pdf', 'image/png', 'image/jpeg'], true)): ?>
                                                    <a class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700" href="<?php echo e($evidence->fileUpload->temporaryPreviewUrl()); ?>" target="_blank" rel="noopener">Vista previa</a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <a class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" href="<?php echo e($evidence->fileUpload->temporaryDownloadUrl()); ?>">Descargar</a>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <form class="mt-4 border-t border-slate-100 pt-4" method="POST" action="<?php echo e(route('participant.professor.teaching.evidences.review', [$activity, $evidence])); ?>">
                                            <?php echo csrf_field(); ?>
                                            <textarea class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" name="reason" rows="2" maxlength="1000" placeholder="Observaciones (obligatorias al rechazar)"></textarea>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <button class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500" type="submit" name="status" value="validada">Validar evidencia</button>
                                                <button class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500" type="submit" name="status" value="rechazada">Rechazar</button>
                                            </div>
                                        </form>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <p class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">Este alumno todavía no ha subido evidencias.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </section>

                        <aside class="space-y-5">
                            <section class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                                <h4 class="font-semibold text-slate-900">Evaluación del alumno</h4>
                                <form class="mt-4 space-y-3" method="POST" action="<?php echo e(route('participant.professor.teaching.enrollments.update', [$activity, $enrollment])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <label class="block">
                                        <span class="mb-1 block text-sm font-medium text-slate-700">Calificación final</span>
                                        <input class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" type="number" name="final_score" value="<?php echo e($enrollment->final_score); ?>" min="0" max="100" step="0.01" required>
                                    </label>
                                    <label class="block">
                                        <span class="mb-1 block text-sm font-medium text-slate-700">Avance</span>
                                        <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="completion_status" required>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['no_iniciado' => 'No iniciado', 'en_progreso' => 'En progreso', 'completado' => 'Completado', 'no_aprobado' => 'No aprobado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($value); ?>" <?php if($enrollment->completion_status === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </label>
                                    <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Guardar evaluación</button>
                                </form>
                            </section>

                            <section class="rounded-xl border border-slate-200 p-4">
                                <h4 class="font-semibold text-slate-900">Constancia del curso</h4>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->completion_status === 'completado'): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($certificate?->fileUpload): ?>
                                        <div class="mt-3 flex items-center justify-between gap-3 rounded-lg bg-emerald-50 px-3 py-2 text-sm">
                                            <span class="font-medium text-emerald-700">Constancia emitida</span>
                                            <a class="font-semibold text-emerald-800 underline" href="<?php echo e($certificate->fileUpload->temporaryDownloadUrl()); ?>">Ver</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <form class="mt-3 space-y-3" method="POST" action="<?php echo e(route('participant.professor.teaching.certificates.store', [$activity, $enrollment])); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <label class="sr-only" for="certificate-<?php echo e($enrollment->id); ?>">Constancia de <?php echo e($enrollment->user?->name); ?></label>
                                        <input class="block w-full text-sm text-slate-600 file:mb-2 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700" id="certificate-<?php echo e($enrollment->id); ?>" type="file" name="certificate" accept="application/pdf" required>
                                        <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit"><?php echo e($certificate ? 'Reemplazar constancia' : 'Subir constancia'); ?></button>
                                    </form>
                                <?php else: ?>
                                    <p class="mt-2 text-sm text-slate-500">La carga se habilita al marcar el curso como completado.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </section>
                        </aside>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">No se encontraron alumnos inscritos con esa búsqueda.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\teaching\show.blade.php ENDPATH**/ ?>