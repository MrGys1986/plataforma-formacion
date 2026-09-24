<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => $enrollment->activity?->name ?? 'Mi formación','description' => 'Consulta tu avance y entrega tus propias evidencias.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($enrollment->activity?->name ?? 'Mi formación'),'description' => 'Consulta tu avance y entrega tus propias evidencias.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"><?php echo e(session('status')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid gap-4 sm:grid-cols-3">
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Inscripción','value' => ucfirst($enrollment->status),'detail' => 'Estado actual']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Inscripción','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($enrollment->status)),'detail' => 'Estado actual']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Avance','value' => ucfirst(str_replace('_', ' ', $enrollment->completion_status)),'detail' => 'Progreso del curso','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Avance','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst(str_replace('_', ' ', $enrollment->completion_status))),'detail' => 'Progreso del curso','tone' => 'emerald']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Calificación','value' => $enrollment->final_score ?? 'Pendiente','detail' => 'Resultado obtenido','tone' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Calificación','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($enrollment->final_score ?? 'Pendiente'),'detail' => 'Resultado obtenido','tone' => 'slate']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
        </div>

        <div class="mt-7 grid gap-6 xl:grid-cols-[1fr_360px]">
            <section class="rounded-2xl border border-brand-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700"><?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => 'evidence']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'evidence']); ?>
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
                    <div><h2 class="font-extrabold text-slate-900">Mis evidencias</h2><p class="mt-0.5 text-sm text-slate-500">Documentos entregados en este curso.</p></div>
                </div>
                <div class="mt-4 space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $enrollment->evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="rounded-lg border border-slate-200 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-900"><?php echo e($evidence->title); ?></p>
                                    <p class="mt-1 text-sm text-slate-500"><?php echo e(ucfirst($evidence->evidence_type)); ?></p>
                                </div>
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700"><?php echo e(ucfirst($evidence->status)); ?></span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($evidence->fileUpload): ?>
                                <a class="mt-3 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-600" href="<?php echo e($evidence->fileUpload->temporaryDownloadUrl()); ?>">Descargar archivo</a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <p class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">Aún no has cargado evidencias para este curso.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </section>

            <section class="h-fit rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-white p-5 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-brand-700 shadow-sm"><?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => 'evidence']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'evidence']); ?>
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
                <h2 class="mt-3 font-extrabold text-slate-900">Subir evidencia</h2>
                <p class="mt-1 text-sm text-slate-500">Agrega tu archivo y la información necesaria.</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->status === 'aprobada'): ?>
                    <form class="mt-4 space-y-4" method="POST" action="<?php echo e(route('participant.evidences.store', $enrollment)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Título</span>
                            <input class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="title" maxlength="255" required>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Tipo</span>
                            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="evidence_type" required>
                                <option value="producto">Producto</option>
                                <option value="participacion">Participación</option>
                                <option value="evaluacion">Evaluación</option>
                                <option value="otro">Otro</option>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Descripción</span>
                            <textarea class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2" name="description" rows="3" maxlength="3000"></textarea>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">Archivo</span>
                            <input class="block w-full text-sm text-slate-600" type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
                        </label>
                        <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-600" type="submit">Cargar evidencia</button>
                    </form>
                <?php else: ?>
                    <p class="mt-3 text-sm text-slate-500">Podrás subir evidencias cuando tu inscripción sea aprobada.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\learning\show.blade.php ENDPATH**/ ?>