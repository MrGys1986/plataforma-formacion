<?php $__env->startSection('title', $portal['title'].' · Plataforma Institucional de Formación y Microcredenciales'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginalf498a2892d24fe70c1bcb460c7eb1967 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf498a2892d24fe70c1bcb460c7eb1967 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.role-dashboard','data' => ['portal' => $portal,'stats' => $stats,'actions' => $portal['actions'],'attention' => $attention,'recent' => $recent]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('role-dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['portal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portal),'stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats),'actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portal['actions']),'attention' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attention),'recent' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recent)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf498a2892d24fe70c1bcb460c7eb1967)): ?>
<?php $attributes = $__attributesOriginalf498a2892d24fe70c1bcb460c7eb1967; ?>
<?php unset($__attributesOriginalf498a2892d24fe70c1bcb460c7eb1967); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf498a2892d24fe70c1bcb460c7eb1967)): ?>
<?php $component = $__componentOriginalf498a2892d24fe70c1bcb460c7eb1967; ?>
<?php unset($__componentOriginalf498a2892d24fe70c1bcb460c7eb1967); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\portal\dashboard.blade.php ENDPATH**/ ?>