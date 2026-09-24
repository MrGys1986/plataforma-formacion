<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'portal',
    'stats' => [],
    'actions' => [],
    'attention' => [],
    'recent' => [],
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'portal',
    'stats' => [],
    'actions' => [],
    'attention' => [],
    'recent' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $pageTitle = $portal['title'];
    $pageDescription = $portal['description'];
    $pageContext = $portal['role'];
?>

<div class="space-y-6 sm:space-y-8">
    <section class="relative isolate overflow-hidden rounded-[1.75rem] bg-brand-950 px-5 py-7 text-white shadow-2xl shadow-brand-950/15 sm:px-8 sm:py-9 lg:px-10">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -right-20 -top-24 h-64 w-64 rounded-full bg-brand-500/25 blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 h-64 w-64 rounded-full bg-blue-400/15 blur-3xl"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>

        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-blue-100">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 ring-4 ring-emerald-400/15"></span>
                    <?php echo e($pageContext); ?>

                </div>
                <h1 class="mt-5 text-3xl font-extrabold tracking-tight sm:text-4xl"><?php echo e($pageTitle); ?></h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100/80 sm:text-base"><?php echo e($pageDescription); ?></p>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.07] px-4 py-3 backdrop-blur-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()?->avatarFile): ?>
                    <img class="h-11 w-11 rounded-xl object-cover ring-2 ring-white/20" src="<?php echo e(auth()->user()->avatarFile->optimizedImageUrl(132, 132)); ?>" alt="Fotografía de <?php echo e(auth()->user()->name); ?>">
                <?php else: ?>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-base font-extrabold text-white"><?php echo e(mb_strtoupper(mb_substr(auth()->user()?->name ?? 'U', 0, 1))); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="min-w-0">
                    <p class="text-xs text-blue-200">Sesión activa</p>
                    <p class="max-w-56 truncate text-sm font-bold text-white"><?php echo e(auth()->user()?->name); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800" role="status">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 7"/></svg>
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800" role="alert">
            <p class="font-extrabold">Revisa la información capturada:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><li><?php echo e($error); ?></li><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section aria-labelledby="dashboard-summary">
        <div class="mb-4 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Resumen</p>
                <h2 id="dashboard-summary" class="mt-1 text-xl font-extrabold text-brand-950">Información relevante</h2>
            </div>
            <span class="hidden text-sm font-medium text-slate-500 sm:block">Datos actualizados</span>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $tone = match($stat['tone'] ?? 'blue') {
                        'emerald' => ['bg-emerald-50 text-emerald-700 ring-emerald-100', 'bg-emerald-500'],
                        'amber' => ['bg-amber-50 text-amber-700 ring-amber-100', 'bg-amber-500'],
                        'violet' => ['bg-violet-50 text-violet-700 ring-violet-100', 'bg-violet-500'],
                        default => ['bg-brand-50 text-brand-700 ring-brand-100', 'bg-brand-500'],
                    };
                ?>
                <article class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-950/[0.07]">
                    <span class="absolute inset-x-0 top-0 h-1 <?php echo e($tone[1]); ?>"></span>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-slate-600"><?php echo e($stat['label']); ?></p>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-brand-950"><?php echo e(number_format($stat['value'])); ?></p>
                        </div>
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 <?php echo e($tone[0]); ?>">
                            <?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => $stat['icon'] ?? 'squares']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stat['icon'] ?? 'squares')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $attributes = $__attributesOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__attributesOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $component = $__componentOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__componentOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
                        </span>
                    </div>
                    <p class="mt-4 text-xs leading-5 text-slate-500"><?php echo e($stat['description']); ?></p>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.45fr)_minmax(18rem,0.75fr)]">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="dashboard-actions">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Navegación</p>
                <h2 id="dashboard-actions" class="mt-1 text-xl font-extrabold text-brand-950">Accesos rápidos</h2>
                <p class="mt-1 text-sm text-slate-500">Las opciones disponibles corresponden a tu rol.</p>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a class="group flex min-h-32 items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition duration-200 hover:-translate-y-0.5 hover:border-brand-300 hover:bg-brand-50 hover:shadow-lg hover:shadow-brand-950/[0.06]" href="<?php echo e($action['url']); ?>">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-700 shadow-sm ring-1 ring-brand-100 transition group-hover:bg-brand-700 group-hover:text-white">
                            <?php if (isset($component)) { $__componentOriginalf6bc322e512402e374c91f18527447fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6bc322e512402e374c91f18527447fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-nav-icon','data' => ['name' => $action['icon'] ?? 'squares']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-nav-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($action['icon'] ?? 'squares')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $attributes = $__attributesOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__attributesOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6bc322e512402e374c91f18527447fb)): ?>
<?php $component = $__componentOriginalf6bc322e512402e374c91f18527447fb; ?>
<?php unset($__componentOriginalf6bc322e512402e374c91f18527447fb); ?>
<?php endif; ?>
                        </span>
                        <span class="min-w-0">
                            <span class="flex items-center gap-2 font-extrabold text-slate-900 group-hover:text-brand-800">
                                <?php echo e($action['label']); ?>

                                <svg class="h-4 w-4 shrink-0 transition group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                            </span>
                            <span class="mt-1.5 block text-sm leading-5 text-slate-500"><?php echo e($action['description']); ?></span>
                        </span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-brand-100 bg-gradient-to-b from-brand-50 to-white shadow-sm" aria-labelledby="dashboard-attention">
            <div class="border-b border-brand-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-700 text-white shadow-md shadow-brand-700/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.6 2.7 18a1.5 1.5 0 0 0 1.3 2.25h16a1.5 1.5 0 0 0 1.3-2.25L13.7 4.6a1.95 1.95 0 0 0-3.4 0Z"/></svg>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-brand-600">Seguimiento</p>
                        <h2 id="dashboard-attention" class="font-extrabold text-brand-950">Atención requerida</h2>
                    </div>
                </div>
            </div>

            <div class="space-y-3 p-5 sm:p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attention; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a class="group flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:border-brand-300 hover:shadow-md" href="<?php echo e($item['url']); ?>">
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-brand-800"><?php echo e($item['label']); ?></span>
                        <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-brand-100 px-2.5 py-1 text-xs font-extrabold text-brand-800"><?php echo e(number_format($item['value'])); ?></span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="rounded-2xl border border-dashed border-emerald-200 bg-emerald-50 px-5 py-7 text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4 4L19 7"/></svg>
                        </span>
                        <p class="mt-3 font-bold text-emerald-900">Todo al día</p>
                        <p class="mt-1 text-sm text-emerald-700">No hay pendientes registrados.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($recent)): ?>
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="dashboard-recent">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-600">Tu actividad</p>
                    <h2 id="dashboard-recent" class="mt-1 text-xl font-extrabold text-brand-950">Inscripciones recientes</h2>
                </div>
                <a class="text-sm font-bold text-brand-700 hover:text-brand-600" href="<?php echo e(route('participant.my-courses.index')); ?>">Ver toda mi formación →</a>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-4 transition hover:border-brand-200 hover:bg-brand-50" href="<?php echo e($item['url']); ?>">
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-slate-800"><?php echo e($item['title']); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['meta']): ?><span class="mt-1 block text-xs text-slate-500"><?php echo e($item['meta']); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-bold text-brand-700 ring-1 ring-brand-100"><?php echo e($item['status']); ?></span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views/components/role-dashboard.blade.php ENDPATH**/ ?>