<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Verificación de constancia','description' => 'La constancia fue localizada en el registro institucional.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Verificación de constancia','description' => 'La constancia fue localizada en el registro institucional.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <dl class="grid gap-3 text-sm md:grid-cols-2">
            <div><dt class="font-semibold">Folio</dt><dd><?php echo e($certificate->folio); ?></dd></div>
            <div><dt class="font-semibold">Titular</dt><dd><?php echo e($certificate->user?->name); ?></dd></div>
            <div><dt class="font-semibold">Actividad</dt><dd><?php echo e($certificate->activity?->name ?? 'No aplica'); ?></dd></div>
            <div><dt class="font-semibold">Estado</dt><dd><?php echo e(ucfirst($certificate->status)); ?></dd></div>
            <div><dt class="font-semibold">Tipo</dt><dd><?php echo e(ucfirst($certificate->certificate_type)); ?></dd></div>
            <div><dt class="font-semibold">Fecha de emisión</dt><dd><?php echo e($certificate->issued_at?->format('d/m/Y') ?? 'Pendiente'); ?></dd></div>
            <div><dt class="font-semibold">Institución emisora</dt><dd><?php echo e(config('app.name')); ?></dd></div>
        </dl>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\public\certificates\verify.blade.php ENDPATH**/ ?>