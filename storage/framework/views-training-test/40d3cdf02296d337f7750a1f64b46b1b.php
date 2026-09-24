<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Perfil docente','description' => 'Administra tu información y consulta tus reconocimientos.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Perfil docente','description' => 'Administra tu información y consulta tus reconocimientos.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <nav class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3" aria-label="Opciones del perfil">
            <a class="flex items-center justify-between rounded-xl border border-violet-200 bg-violet-50 px-5 py-4 font-semibold text-violet-700 transition hover:border-violet-300 hover:bg-violet-100" href="<?php echo e(route('participant.badges.index')); ?>">
                <span>Mis insignias</span>
                <span aria-hidden="true">→</span>
            </a>
            <a class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100" href="<?php echo e(route('participant.certificates.index')); ?>">
                <span>Mis constancias</span>
                <span aria-hidden="true">→</span>
            </a>
            <a class="flex items-center justify-between rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-100" href="#fotografia-perfil">
                <span>Cambiar fotografía de perfil</span>
                <span aria-hidden="true">↓</span>
            </a>
        </nav>

        <section id="fotografia-perfil" class="flex scroll-mt-6 flex-col gap-5 rounded-xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->avatarFile): ?>
                <img class="h-24 w-24 rounded-full object-cover ring-4 ring-white" src="<?php echo e(auth()->user()->avatarFile->optimizedImageUrl(240, 240)); ?>" alt="Fotografía de <?php echo e(auth()->user()->name); ?>">
            <?php else: ?>
                <span class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 text-3xl font-bold text-blue-700"><?php echo e(mb_strtoupper(mb_substr(auth()->user()->name, 0, 1))); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex-1"><h2 class="font-semibold text-slate-900">Fotografía de perfil</h2><p class="mt-1 text-sm text-slate-500">JPG, PNG o WebP. Cloudinary genera automáticamente los tamaños optimizados.</p><form class="mt-3 flex flex-col gap-3 sm:flex-row" method="POST" action="<?php echo e(route('participant.professor.profile.avatar')); ?>" enctype="multipart/form-data"><?php echo csrf_field(); ?><input class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-4 file:py-2 file:font-semibold file:text-blue-700" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white" type="submit">Actualizar foto</button></form><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
        </section>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\participant\professor-profile\index.blade.php ENDPATH**/ ?>