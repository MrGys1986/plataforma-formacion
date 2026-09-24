<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Constancias','description' => 'Constancias y certificaciones generadas mediante capacitación interna.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Constancias','description' => 'Constancias y certificaciones generadas mediante capacitación interna.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <form class="mb-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_210px_auto]" method="GET" action="<?php echo e(route('rh.certificates.index')); ?>" data-auto-filter>
        <input class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm" type="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Buscar participante, folio o actividad">
        <select class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="status">
            <option value="">Todos los estados</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['disponible' => 'Disponible','descargada' => 'Descargada','pendiente' => 'Pendiente','bloqueada' => 'Bloqueada','reemitida' => 'Reemitida','cancelada' => 'Cancelada']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </select>
        <?php if (isset($component)) { $__componentOriginal50597db1f45b00e1436c6f7332fec915 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50597db1f45b00e1436c6f7332fec915 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auto-filter-actions','data' => ['resetUrl' => route('rh.certificates.index'),'active' => request()->hasAny(['search','status'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auto-filter-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('rh.certificates.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search','status']))]); ?>
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

    <p class="mb-4 text-sm text-slate-500"><span class="font-semibold text-slate-800"><?php echo e($certificates->total()); ?></span> constancias encontradas</p>
    <div class="grid gap-5 xl:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 bg-slate-50 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr($certificate->user?->name ?? '?',0,1))); ?></span><div class="min-w-0"><p class="truncate font-semibold text-slate-900"><?php echo e($certificate->user?->name ?? 'Usuario no disponible'); ?></p><p class="truncate text-sm text-slate-500"><?php echo e($certificate->user?->area?->name ?? 'Sin área asignada'); ?></p></div></div>
                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => in_array($certificate->status,['disponible','descargada','reemitida','emitida'],true),'bg-amber-100 text-amber-700' => in_array($certificate->status,['pendiente','bloqueada'],true),'bg-red-100 text-red-700' => $certificate->status === 'cancelada']); ?>"><?php echo e(ucfirst($certificate->status)); ?></span>
                </div>
            </div>
            <div class="p-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700"><?php echo e($certificate->activity?->activityType?->name ?? 'Actividad'); ?></p><h2 class="mt-2 font-semibold text-slate-900"><?php echo e($certificate->activity?->name ?? 'Actividad no disponible'); ?></h2>
                <dl class="mt-5 grid gap-4 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                    <div><dt class="font-semibold text-slate-500">Folio</dt><dd class="mt-1 text-slate-800"><?php echo e($certificate->folio); ?></dd></div>
                    <div><dt class="font-semibold text-slate-500">Emisión</dt><dd class="mt-1 text-slate-800"><?php echo e($certificate->issued_at?->format('d/m/Y') ?? 'Pendiente'); ?></dd></div>
                    <div><dt class="font-semibold text-slate-500">Calificación</dt><dd class="mt-1 text-slate-800"><?php echo e($certificate->enrollment?->final_score ?? 'Sin registro'); ?></dd></div>
                    <div><dt class="font-semibold text-slate-500">Responsable</dt><dd class="mt-1 text-slate-800"><?php echo e($certificate->issuedBy?->name ?? 'Sin registro'); ?></dd></div>
                </dl>
                <a class="mt-5 inline-flex rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800" href="<?php echo e(route('public.certificates.verify',$certificate->folio)); ?>" target="_blank" rel="noopener">Verificar constancia</a>
            </div>
        </article>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No se encontraron constancias con los filtros seleccionados.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($certificates->hasPages()): ?><div class="mt-6"><?php echo e($certificates->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\rh\certificates\index.blade.php ENDPATH**/ ?>