<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <div class="pf-topbar-user-name" aria-label="Usuario actual">
        <span><?php echo e(filament()->getUserName(filament()->auth()->user())); ?></span>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\filament\partials\user-name.blade.php ENDPATH**/ ?>