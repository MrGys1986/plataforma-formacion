<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Mis pagos','description' => 'Consulta tus pagos, comprobantes y el estado de validación de cada movimiento.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mis pagos','description' => 'Consulta tus pagos, comprobantes y el estado de validación de cada movimiento.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">Movimientos</p>
                <p class="mt-2 text-3xl font-bold text-slate-950"><?php echo e($payments->total()); ?></p>
            </div>
            <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                <p class="text-sm font-medium text-amber-700">Pendientes en esta página</p>
                <p class="mt-2 text-3xl font-bold text-slate-950"><?php echo e($payments->getCollection()->where('status', 'pendiente')->count()); ?></p>
            </div>
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-sm font-medium text-emerald-700">Validados en esta página</p>
                <p class="mt-2 text-3xl font-bold text-slate-950"><?php echo e($payments->getCollection()->whereIn('status', ['validado', 'pagado'])->count()); ?></p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $statusClass = match($payment->status) {
                        'validado', 'pagado' => 'bg-emerald-100 text-emerald-700',
                        'rechazado' => 'bg-rose-100 text-rose-700',
                        default => 'bg-amber-100 text-amber-700',
                    };
                ?>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="font-bold text-slate-950"><?php echo e($payment->activity?->name ?? 'Curso no disponible'); ?></h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold <?php echo e($statusClass); ?>"><?php echo e(ucfirst($payment->status)); ?></span>
                            </div>
                            <dl class="mt-4 grid gap-x-8 gap-y-3 text-sm sm:grid-cols-2">
                                <div><dt class="font-semibold text-slate-500">Fecha</dt><dd class="mt-1 text-slate-800"><?php echo e($payment->created_at?->format('d/m/Y H:i')); ?></dd></div>
                                <div><dt class="font-semibold text-slate-500">Método</dt><dd class="mt-1 text-slate-800"><?php echo e(ucfirst($payment->payment_method ?? 'manual')); ?></dd></div>
                                <div><dt class="font-semibold text-slate-500">Referencia</dt><dd class="mt-1 text-slate-800"><?php echo e($payment->payment_reference ?: 'Sin referencia'); ?></dd></div>
                                <div><dt class="font-semibold text-slate-500">Validación</dt><dd class="mt-1 text-slate-800"><?php echo e($payment->validated_at?->format('d/m/Y H:i') ?? 'Pendiente de revisión'); ?></dd></div>
                            </dl>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment->observations): ?>
                                <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700"><span class="font-semibold">Observaciones:</span> <?php echo e($payment->observations); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="shrink-0 lg:text-right">
                            <p class="text-2xl font-bold text-slate-950">$<?php echo e(number_format((float) $payment->amount, 2)); ?> <span class="text-xs font-semibold text-slate-500"><?php echo e($payment->currency); ?></span></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment->proofFile): ?>
                                <div class="mt-4 flex flex-wrap gap-2 lg:justify-end">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($payment->proofFile->mime_type, ['application/pdf', 'image/png', 'image/jpeg'], true)): ?>
                                        <a class="rounded-lg border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50" href="<?php echo e($payment->proofFile->temporaryPreviewUrl()); ?>" target="_blank" rel="noopener">Ver comprobante</a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <a class="rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800" href="<?php echo e($payment->proofFile->temporaryDownloadUrl()); ?>">Descargar</a>
                                </div>
                            <?php else: ?>
                                <p class="mt-3 text-sm text-slate-500">Sin comprobante adjunto.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                    <p class="font-semibold text-slate-800">Todavía no tienes pagos registrados.</p>
                    <p class="mt-2 text-sm text-slate-500">Cuando envíes un comprobante, podrás consultar aquí su estado.</p>
                    <a class="mt-5 inline-flex rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white" href="<?php echo e(route('participant.catalog.index')); ?>">Explorar catálogo</a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payments->hasPages()): ?>
            <div class="mt-6"><?php echo e($payments->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\payments\index.blade.php ENDPATH**/ ?>