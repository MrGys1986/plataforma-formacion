<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginald467456a367f78493b5e3d963d2420fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald467456a367f78493b5e3d963d2420fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-page','data' => ['title' => 'Reportes de calidad','description' => 'Concentrado institucional para consulta y preparación de informes.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reportes de calidad','description' => 'Concentrado institucional para consulta y preparación de informes.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><?php if (isset($component)) { $__componentOriginala43fa4191d7a194238fa361901009107 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala43fa4191d7a194238fa361901009107 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Evidencias','value' => $evidences['total'],'detail' => 'Expedientes registrados']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Evidencias','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['total']),'detail' => 'Expedientes registrados']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Validadas','value' => $evidences['validadas'],'detail' => 'Listas para reporte','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Validadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['validadas']),'detail' => 'Listas para reporte','tone' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Pendientes','value' => $evidences['pendientes'],'detail' => 'En proceso de revisión','tone' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pendientes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['pendientes']),'detail' => 'En proceso de revisión','tone' => 'amber']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-stat','data' => ['label' => 'Constancias','value' => $certificates['total'],'detail' => 'Resultados documentados','tone' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Constancias','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($certificates['total']),'detail' => 'Resultados documentados','tone' => 'slate']); ?>
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
    <div class="mt-6 grid gap-6 xl:grid-cols-[1.25fr_1fr]">
        <section class="overflow-hidden rounded-xl border border-slate-200"><div class="border-b border-slate-200 bg-slate-50 px-5 py-4"><h2 class="font-semibold">Reportes disponibles</h2><p class="mt-1 text-xs text-slate-500">Ejemplos de cortes institucionales</p></div><div class="divide-y divide-slate-100"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['Resumen de evidencias','Estatus y distribución de expedientes académicos','Actualizado hoy'],['Cumplimiento de acreditación','Concentrado CACEI, ABET e ISO','Corte agosto 2026'],['Resultados de evaluación','Aprobación y desempeño por actividad','Ciclo 2025–2026'],['Seguimiento de mejora','Avance y vencimiento de planes de acción','Actualizado hoy']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><article class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between"><div><h3 class="font-semibold text-slate-900"><?php echo e($report[0]); ?></h3><p class="mt-1 text-sm text-slate-500"><?php echo e($report[1]); ?></p><p class="mt-2 text-xs font-medium text-blue-600"><?php echo e($report[2]); ?></p></div><button class="shrink-0 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-400 hover:text-blue-700" type="button">Ver reporte</button></article><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></div></section>
        <aside class="space-y-6"><section class="rounded-xl border border-slate-200 p-5"><h2 class="font-semibold">Distribución de evidencias</h2><div class="mt-5 space-y-5"><?php ($total = max(1, $evidences['total'])); ?><?php if (isset($component)) { $__componentOriginal3bfd6e0395da7fe0fd60c019542de618 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3bfd6e0395da7fe0fd60c019542de618 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Validadas','value' => round($evidences['validadas'] / $total * 100),'detail' => $evidences['validadas'].' expedientes','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Validadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($evidences['validadas'] / $total * 100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['validadas'].' expedientes'),'tone' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Pendientes','value' => round($evidences['pendientes'] / $total * 100),'detail' => $evidences['pendientes'].' expedientes','tone' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pendientes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($evidences['pendientes'] / $total * 100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['pendientes'].' expedientes'),'tone' => 'amber']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.quality-progress','data' => ['label' => 'Rechazadas','value' => round($evidences['rechazadas'] / $total * 100),'detail' => $evidences['rechazadas'].' expedientes','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('quality-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Rechazadas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(round($evidences['rechazadas'] / $total * 100)),'detail' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evidences['rechazadas'].' expedientes'),'tone' => 'rose']); ?>
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
<?php endif; ?></div></section><section class="rounded-xl bg-slate-900 p-5 text-white"><p class="text-xs font-bold uppercase tracking-wider text-blue-300">Próximo corte</p><p class="mt-2 text-2xl font-bold">31 de agosto</p><p class="mt-2 text-sm text-slate-300">Consolida las evidencias pendientes antes de generar el informe mensual.</p></section></aside>
    </div>
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\quality\reports\index.blade.php ENDPATH**/ ?>