<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Participantes del área','description' => 'Usuarios adscritos a tu área: '.e($users->total()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Participantes del área','description' => 'Usuarios adscritos a tu área: '.e($users->total()).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <div class="hidden grid-cols-[minmax(0,1.5fr)_minmax(0,1.5fr)_minmax(0,1fr)_auto] gap-4 bg-slate-50 px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 md:grid">
                <span>Usuario</span>
                <span>Correo</span>
                <span>Rol</span>
                <span>Estado</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <article class="grid gap-3 border-t border-slate-200 px-5 py-4 first:border-t-0 md:grid-cols-[minmax(0,1.5fr)_minmax(0,1.5fr)_minmax(0,1fr)_auto] md:items-center md:gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                            <?php echo e(mb_strtoupper(mb_substr($user->name, 0, 1))); ?>

                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-slate-900"><?php echo e($user->name); ?></p>
                            <p class="mt-0.5 text-xs text-slate-500 md:hidden"><?php echo e($user->area?->name ?? 'Sin área asignada'); ?></p>
                        </div>
                    </div>
                    <p class="truncate text-sm text-slate-600">
                        <span class="font-semibold text-slate-500 md:hidden">Correo: </span><?php echo e($user->email); ?>

                    </p>
                    <p class="text-sm text-slate-600">
                        <span class="font-semibold text-slate-500 md:hidden">Rol: </span><?php echo e($user->getRoleNames()->first() ?? 'Sin rol'); ?>

                    </p>
                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'w-fit rounded-full px-3 py-1 text-xs font-semibold',
                        'bg-emerald-100 text-emerald-700' => $user->status === 'activo',
                        'bg-slate-100 text-slate-600' => $user->status !== 'activo',
                    ]); ?>">
                        <?php echo e(ucfirst($user->status ?? 'sin estado')); ?>

                    </span>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">No hay participantes registrados en tu área.</p>
                    <p class="mt-2 text-sm text-slate-500">Asigna usuarios a esta área desde la administración para que aparezcan aquí.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->hasPages()): ?>
            <div class="mt-6"><?php echo e($users->links()); ?></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\area-manager\participants\index.blade.php ENDPATH**/ ?>