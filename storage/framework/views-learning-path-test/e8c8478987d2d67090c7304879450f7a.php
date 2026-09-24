<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Reportes de educación continua','description' => 'Indicadores consolidados de inscripción, avance y emisión de constancias.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reportes de educación continua','description' => 'Indicadores consolidados de inscripción, avance y emisión de constancias.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid gap-4 sm:grid-cols-4"><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Inscripciones','value' => $training['total']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Inscripciones','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($training['total'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Aprobadas','value' => $training['aprobadas'],'tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Aprobadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($training['aprobadas']),'tone' => 'emerald']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Completadas','value' => $training['completadas']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Completadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($training['completadas'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Constancias','value' => $certificates['total'],'tone' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Constancias','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates['total']),'tone' => 'slate']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $attributes = $__attributesOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__attributesOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala43fa4191d7a194238fa361901009107)): ?>
<?php $component = $__componentOriginala43fa4191d7a194238fa361901009107; ?>
<?php unset($__componentOriginala43fa4191d7a194238fa361901009107); ?>
<?php endif; ?></div>
    <?php ($total = max(1,$training['total'])); ?>
    <div class="mt-6 grid gap-6 lg:grid-cols-[1.2fr_1fr]"><section class="rounded-xl border border-slate-200 p-5"><h2 class="font-semibold">Indicadores del periodo</h2><div class="mt-5 space-y-5"><?php if (isset($component)) { $__componentOriginal3bfd6e0395da7fe0fd60c019542de618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Solicitudes aprobadas','value' => round($training['aprobadas']/$total*100),'detail' => $training['aprobadas'].' inscripciones','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Solicitudes aprobadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($training['aprobadas']/$total*100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($training['aprobadas'].' inscripciones'),'tone' => 'emerald']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $attributes = $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $component = $__componentOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal3bfd6e0395da7fe0fd60c019542de618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Participantes que completaron','value' => round($training['completadas']/$total*100),'detail' => $training['completadas'].' participantes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Participantes que completaron','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($training['completadas']/$total*100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($training['completadas'].' participantes')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $attributes = $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $component = $__componentOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal3bfd6e0395da7fe0fd60c019542de618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Constancias emitidas','value' => round($certificates['emitidas']/$total*100),'detail' => $certificates['emitidas'].' documentos','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Constancias emitidas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($certificates['emitidas']/$total*100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates['emitidas'].' documentos'),'tone' => 'emerald']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $attributes = $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__attributesOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618)): ?>
<?php $component = $__componentOriginal3bfd6e0395da7fe0fd60c019542de618; ?>
<?php unset($__componentOriginal3bfd6e0395da7fe0fd60c019542de618); ?>
<?php endif; ?></div></section><section class="rounded-xl border border-slate-200 bg-slate-50 p-5"><h2 class="font-semibold">Reportes disponibles</h2><div class="mt-4 space-y-3"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Inscripciones por actividad','Ingresos y pagos','Finalización y constancias','Participantes por institución']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><button class="flex w-full items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-700 hover:border-blue-300" type="button"><span><?php echo e($report); ?></span><span class="text-blue-600">→</span></button><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></div></section></div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\continuing-education\reports\index.blade.php ENDPATH**/ ?>