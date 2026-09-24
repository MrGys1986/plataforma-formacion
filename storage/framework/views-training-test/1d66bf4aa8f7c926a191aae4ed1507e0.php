<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Acceso seguro a la Plataforma Institucional de Formación y Microcredenciales.">
    <title><?php echo $__env->yieldContent('title', 'Acceso'); ?> · <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-brand-50 text-slate-900 antialiased">
    <div class="flex min-h-screen flex-col">
        <?php if (isset($component)) { $__componentOriginal2669d93ac3865159955b6d09c48349b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2669d93ac3865159955b6d09c48349b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public-header','data' => ['current' => 'login']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'login']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2669d93ac3865159955b6d09c48349b9)): ?>
<?php $attributes = $__attributesOriginal2669d93ac3865159955b6d09c48349b9; ?>
<?php unset($__attributesOriginal2669d93ac3865159955b6d09c48349b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2669d93ac3865159955b6d09c48349b9)): ?>
<?php $component = $__componentOriginal2669d93ac3865159955b6d09c48349b9; ?>
<?php unset($__componentOriginal2669d93ac3865159955b6d09c48349b9); ?>
<?php endif; ?>

        <main class="relative isolate flex flex-1 items-center overflow-hidden px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
            <div class="pointer-events-none absolute inset-0 -z-10">
                <div class="absolute -left-40 top-16 h-96 w-96 rounded-full bg-white blur-3xl"></div>
                <div class="absolute -right-40 bottom-0 h-[30rem] w-[30rem] rounded-full bg-brand-200/55 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#0d559508_1px,transparent_1px),linear-gradient(to_bottom,#0d559508_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            </div>

            <div class="mx-auto grid w-full max-w-6xl overflow-hidden rounded-[1.75rem] border border-brand-100 bg-white shadow-2xl shadow-brand-950/15 lg:grid-cols-[1.02fr_0.98fr]">
                <section class="relative isolate overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 px-6 py-9 text-white sm:px-10 sm:py-11 lg:flex lg:min-h-[650px] lg:flex-col lg:justify-between lg:p-12">
                    <div class="pointer-events-none absolute inset-0 -z-10">
                        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full border-[44px] border-white/5"></div>
                        <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full bg-brand-400/20 blur-3xl"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(255,255,255,0.13),transparent_27%)]"></div>
                    </div>

                    <div class="max-w-lg">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 text-xs font-bold uppercase tracking-[0.16em] text-blue-100 backdrop-blur-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-200"></span>
                            Acceso institucional seguro
                        </span>
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">Tu aprendizaje y tus logros en un solo espacio.</h1>
                        <p class="mt-4 max-w-md text-sm leading-7 text-blue-100/80 sm:text-base">Ingresa para descubrir oportunidades y continuar avanzando en tu formación.</p>
                    </div>

                    <?php if (! empty(trim($__env->yieldContent('auth-illustration')))): ?>
                        <div class="mx-auto mt-7 hidden w-full max-w-sm items-center justify-center sm:flex lg:my-6">
                            <?php echo $__env->yieldContent('auth-illustration'); ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mt-8 hidden grid-cols-3 gap-3 border-t border-white/15 pt-6 text-center text-xs font-semibold text-blue-100/75 sm:grid lg:mt-0">
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Fácil de usar</div>
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Acceso seguro</div>
                        <div class="rounded-xl bg-white/[0.07] px-2 py-3">Siempre disponible</div>
                    </div>
                </section>

                <section class="flex items-center px-6 py-9 sm:px-10 sm:py-11 lg:px-12 xl:px-16">
                    <div class="mx-auto w-full max-w-md">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('social')): ?>
                            <div class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                <?php echo e($errors->first('social')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                            <div class="mb-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                <?php echo e(session('status')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </section>
            </div>
        </main>

        <?php if (isset($component)) { $__componentOriginal2702f386a0a6c0cb365c22db0bdb7e06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2702f386a0a6c0cb365c22db0bdb7e06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2702f386a0a6c0cb365c22db0bdb7e06)): ?>
<?php $attributes = $__attributesOriginal2702f386a0a6c0cb365c22db0bdb7e06; ?>
<?php unset($__attributesOriginal2702f386a0a6c0cb365c22db0bdb7e06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2702f386a0a6c0cb365c22db0bdb7e06)): ?>
<?php $component = $__componentOriginal2702f386a0a6c0cb365c22db0bdb7e06; ?>
<?php unset($__componentOriginal2702f386a0a6c0cb365c22db0bdb7e06); ?>
<?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\layouts\auth.blade.php ENDPATH**/ ?>