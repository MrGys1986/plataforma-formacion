<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plataforma Institucional de Formación y Microcredenciales de la Universidad Aeronáutica en Querétaro.">
    <title><?php echo $__env->yieldContent('title', 'Plataforma Institucional de Formación y Microcredenciales'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<?php ($portal = $portal ?? \App\Support\PortalConfiguration::forUser(auth()->user())); ?>
<?php ($isOperationalPortal = auth()->check() && $portal['key'] !== 'unsupported'); ?>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-50 w-full bg-brand-950 text-white shadow-xl shadow-brand-950/10">
        <div class="mx-auto flex min-h-20 w-full max-w-[100rem] items-center justify-between gap-4 px-4 py-3 sm:min-h-24 sm:px-6">
            <a class="flex min-w-0 items-center gap-3 sm:gap-4" href="<?php echo e($isOperationalPortal ? route('dashboard') : route('home')); ?>" aria-label="Ir al inicio">
                <img
                    class="h-12 w-auto shrink-0 object-contain sm:h-16"
                    src="<?php echo e(asset('img/LogoUNAQ.webp')); ?>"
                    alt="Universidad Aeronáutica en Querétaro"
                >
                <span class="hidden h-11 w-px bg-white/20 sm:block"></span>
                <span class="hidden min-w-0 sm:block">
                    <span class="block text-xs font-medium leading-5 text-blue-100/75">Plataforma Institucional de Formación y Microcredenciales</span>
                    <span class="block truncate text-sm font-extrabold text-white"><?php echo e($portal['name']); ?></span>
                </span>
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <details class="group relative">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center gap-2.5 rounded-xl border border-white/15 bg-white/[0.07] px-2 py-1.5 transition hover:border-white/30 hover:bg-white/15 focus:outline-none focus:ring-4 focus:ring-white/15 sm:gap-3 sm:px-3 sm:py-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->avatarFile): ?>
                            <img class="h-9 w-9 rounded-lg object-cover ring-2 ring-white/20 sm:h-10 sm:w-10 sm:rounded-xl" src="<?php echo e(auth()->user()->avatarFile->optimizedImageUrl(120, 120)); ?>" alt="Fotografía de <?php echo e(auth()->user()->name); ?>">
                        <?php else: ?>
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-sm font-extrabold text-white shadow-lg shadow-brand-950/30 sm:h-10 sm:w-10 sm:rounded-xl"><?php echo e(mb_strtoupper(mb_substr(auth()->user()->name, 0, 1))); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span class="hidden max-w-44 text-left md:block">
                            <span class="block truncate text-sm font-bold text-white"><?php echo e(auth()->user()->name); ?></span>
                            <span class="block truncate text-xs text-blue-100/70"><?php echo e($portal['role']); ?></span>
                        </span>
                        <svg class="h-4 w-4 text-blue-100/70 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </summary>

                    <div class="absolute right-0 z-50 mt-3 w-[min(19rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-brand-100 bg-white text-slate-700 shadow-2xl shadow-brand-950/25">
                        <div class="flex items-center gap-3 border-b border-slate-100 bg-gradient-to-r from-brand-50 to-white px-5 py-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->avatarFile): ?>
                                <img class="h-12 w-12 shrink-0 rounded-xl object-cover" src="<?php echo e(auth()->user()->avatarFile->optimizedImageUrl(144, 144)); ?>" alt="Fotografía de <?php echo e(auth()->user()->name); ?>">
                            <?php else: ?>
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-100 font-extrabold text-brand-700"><?php echo e(mb_strtoupper(mb_substr(auth()->user()->name, 0, 1))); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900"><?php echo e(auth()->user()->name); ?></p>
                                <p class="truncate text-sm text-slate-500"><?php echo e(auth()->user()->email); ?></p>
                                <span class="mt-2 inline-flex rounded-full bg-brand-100 px-2.5 py-1 text-xs font-bold text-brand-700"><?php echo e($portal['role']); ?></span>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($portal['profileRoute'] ?? null)): ?>
                            <div class="border-b border-slate-100 p-2">
                                <a class="flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-bold text-brand-700 transition hover:bg-brand-50" href="<?php echo e(route($portal['profileRoute'])); ?>"><?php echo e($portal['profileLabel']); ?></a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <form class="p-3" method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-brand-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-100" type="submit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"/><path stroke-linecap="round" stroke-linejoin="round" d="m18 15 3-3m0 0-3-3m3 3H9"/></svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </details>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOperationalPortal && count($portal['navigation'])): ?>
            <div class="hidden border-t border-white/10 bg-brand-900/70 md:block">
                <nav class="overflow-x-auto px-4 py-2.5 sm:px-6" aria-label="Navegación principal">
                    <div class="mx-auto flex w-max min-w-full max-w-[100rem] items-center justify-center gap-1.5">
                        <?php if (isset($component)) { $__componentOriginald484d84b36c257aff90047e3808e1b6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald484d84b36c257aff90047e3808e1b6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-navigation','data' => ['items' => $portal['navigation'],'variant' => 'header']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portal['navigation']),'variant' => 'header']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald484d84b36c257aff90047e3808e1b6f)): ?>
<?php $attributes = $__attributesOriginald484d84b36c257aff90047e3808e1b6f; ?>
<?php unset($__attributesOriginald484d84b36c257aff90047e3808e1b6f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald484d84b36c257aff90047e3808e1b6f)): ?>
<?php $component = $__componentOriginald484d84b36c257aff90047e3808e1b6f; ?>
<?php unset($__componentOriginald484d84b36c257aff90047e3808e1b6f); ?>
<?php endif; ?>
                    </div>
                </nav>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </header>

    <div class="relative isolate w-full flex-1 overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -right-40 top-10 h-96 w-96 rounded-full bg-brand-100/60 blur-3xl"></div>
            <div class="absolute -left-48 top-80 h-80 w-80 rounded-full bg-blue-100/40 blur-3xl"></div>
        </div>

        <div class="mx-auto w-full max-w-[100rem] px-4 py-4 sm:px-6 sm:py-7 lg:py-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOperationalPortal && count($portal['navigation'])): ?>
                <details class="group mb-4 overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm md:hidden">
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-4 px-4 py-3 font-bold text-brand-950 sm:px-5">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-100 text-brand-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </span>
                            <span class="truncate">Menú · <?php echo e($portal['role']); ?></span>
                        </span>
                        <svg class="h-5 w-5 shrink-0 text-brand-600 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <nav class="max-h-[65vh] space-y-1.5 overflow-y-auto border-t border-slate-100 p-3 text-sm sm:p-4" aria-label="Navegación móvil">
                        <?php if (isset($component)) { $__componentOriginald484d84b36c257aff90047e3808e1b6f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald484d84b36c257aff90047e3808e1b6f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.portal-navigation','data' => ['items' => $portal['navigation']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('portal-navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portal['navigation'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald484d84b36c257aff90047e3808e1b6f)): ?>
<?php $attributes = $__attributesOriginald484d84b36c257aff90047e3808e1b6f; ?>
<?php unset($__attributesOriginald484d84b36c257aff90047e3808e1b6f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald484d84b36c257aff90047e3808e1b6f)): ?>
<?php $component = $__componentOriginald484d84b36c257aff90047e3808e1b6f; ?>
<?php unset($__componentOriginald484d84b36c257aff90047e3808e1b6f); ?>
<?php endif; ?>
                    </nav>
                </details>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <main class="portal-content min-w-0">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <footer class="mt-auto w-full border-t border-brand-800 bg-brand-950 text-white">
        <div class="mx-auto flex w-full max-w-[100rem] flex-col items-center justify-between gap-3 px-4 py-5 text-center sm:px-6 md:flex-row md:text-left">
            <div class="flex items-center gap-3">
                <img class="h-9 w-auto" src="<?php echo e(asset('img/LogoUNAQ.webp')); ?>" alt="Universidad Aeronáutica en Querétaro">
                <div>
                    <p class="text-xs font-bold text-white">Plataforma Institucional de Formación y Microcredenciales</p>
                    <p class="mt-0.5 text-xs text-blue-100/60"><?php echo e($portal['name']); ?></p>
                </div>
            </div>
            <p class="text-xs text-blue-100/60">© <?php echo e(date('Y')); ?> Universidad Aeronáutica en Querétaro</p>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views/layouts/portal.blade.php ENDPATH**/ ?>