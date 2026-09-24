<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Personal','description' => 'Directorio y seguimiento de formación del personal institucional.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Personal','description' => 'Directorio y seguimiento de formación del personal institucional.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <form class="mb-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-[minmax(0,1fr)_220px_180px_auto]" method="GET" action="<?php echo e(route('rh.staff.index')); ?>" data-auto-filter>
        <label>
            <span class="sr-only">Buscar personal</span>
            <input class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" type="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Buscar por nombre o correo">
        </label>
        <label>
            <span class="sr-only">Filtrar por área</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="area">
                <option value="">Todas las áreas</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><option value="<?php echo e($area->id); ?>" <?php if((string) request('area') === (string) $area->id): echo 'selected'; endif; ?>><?php echo e($area->name); ?></option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
        </label>
        <label>
            <span class="sr-only">Filtrar por estado</span>
            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm" name="status">
                <option value="">Todos los estados</option>
                <option value="activo" <?php if(request('status') === 'activo'): echo 'selected'; endif; ?>>Activo</option>
                <option value="inactivo" <?php if(request('status') === 'inactivo'): echo 'selected'; endif; ?>>Inactivo</option>
            </select>
        </label>
        <?php if (isset($component)) { $__componentOriginal50597db1f45b00e1436c6f7332fec915 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50597db1f45b00e1436c6f7332fec915 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auto-filter-actions','data' => ['resetUrl' => route('rh.staff.index'),'active' => request()->hasAny(['search','area','status'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auto-filter-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reset-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('rh.staff.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->hasAny(['search','area','status']))]); ?>
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

    <div class="mb-4 flex items-center justify-between gap-4">
        <p class="text-sm text-slate-500"><span class="font-semibold text-slate-800"><?php echo e($users->total()); ?></span> usuarios encontrados</p>
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <a class="group rounded-xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-md" href="<?php echo e(route('rh.staff.show', $user)); ?>">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr($user->name, 0, 1))); ?></span>
                    <div class="min-w-0"><h2 class="truncate font-semibold text-slate-900 group-hover:text-blue-700"><?php echo e($user->name); ?></h2><p class="mt-1 truncate text-sm text-slate-500"><?php echo e($user->email); ?></p></div>
                </div>
                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['shrink-0 rounded-full px-3 py-1 text-xs font-semibold','bg-emerald-100 text-emerald-700' => $user->status === 'activo','bg-slate-100 text-slate-600' => $user->status !== 'activo']); ?>"><?php echo e(ucfirst($user->status ?? 'sin estado')); ?></span>
            </div>
            <div class="mt-5 grid gap-3 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
                <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rol</p><p class="mt-1 font-medium text-slate-700"><?php echo e($user->getRoleNames()->join(', ') ?: 'Sin rol'); ?></p></div>
                <div><p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Área</p><p class="mt-1 font-medium text-slate-700"><?php echo e($user->area?->name ?? 'Sin área asignada'); ?></p></div>
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm">
                <div class="flex gap-2"><span class="rounded-full bg-blue-50 px-3 py-1 font-semibold text-blue-700"><?php echo e($user->enrollments_count); ?> capacitaciones</span><span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700"><?php echo e($user->certificates_count); ?> constancias</span></div>
                <span class="font-semibold text-blue-700">Ver expediente →</span>
            </div>
        </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?><div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500 xl:col-span-2">No se encontró personal con los filtros seleccionados.</div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->hasPages()): ?><div class="mt-6"><?php echo e($users->links()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\rh\staff\index.blade.php ENDPATH**/ ?>