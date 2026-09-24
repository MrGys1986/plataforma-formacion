<div class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Actividad</p>
        <p class="mt-1 truncate font-semibold text-slate-900"><?php echo e($activity->name); ?></p>
    </div>
    <nav class="flex flex-wrap gap-2 text-sm" aria-label="Gestión de la actividad">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
            ['label' => 'Resumen', 'route' => 'personal.courses.show'],
            ['label' => 'Participantes', 'route' => 'personal.courses.participants'],
            ['label' => 'Asistencia', 'route' => 'personal.attendance.index'],
            ['label' => 'Evidencias', 'route' => 'personal.evidences.index'],
            ['label' => 'Evaluaciones', 'route' => 'personal.evaluations.index'],
            ['label' => 'Constancias', 'route' => 'personal.certificates.index'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'rounded-lg border px-3 py-2 font-semibold transition',
                    'border-blue-200 bg-blue-100 text-blue-800' => request()->routeIs($item['route']),
                    'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:text-blue-700' => ! request()->routeIs($item['route']),
                ]); ?>"
                href="<?php echo e(route($item['route'], $activity)); ?>"
            ><?php echo e($item['label']); ?></a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </nav>
</div>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\personal\courses\partials\management-nav.blade.php ENDPATH**/ ?>