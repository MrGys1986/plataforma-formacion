<?php $__env->startSection('portal-name', 'Consulta pública'); ?>

<?php $__env->startSection('navigation'); ?>
    <a class="block rounded px-3 py-2 hover:bg-slate-100" href="<?php echo e(route('home')); ?>">Página principal</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\layouts\public.blade.php ENDPATH**/ ?>