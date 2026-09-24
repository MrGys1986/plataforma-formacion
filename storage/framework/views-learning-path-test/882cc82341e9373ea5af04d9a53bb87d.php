<?php $__env->startSection('title', 'Iniciar sesión'); ?>

<?php $__env->startSection('auth-illustration'); ?>
    <img
        class="h-48 w-full object-contain drop-shadow-2xl sm:h-56 lg:h-64"
        src="<?php echo e(asset('img/login.svg')); ?>"
        alt="Ilustración de acceso seguro a la plataforma"
    >
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="border-b border-slate-200 pb-6">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Bienvenido</p>
        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-brand-950 sm:text-4xl">Inicia sesión</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
            Usa el correo electrónico y la contraseña asignados por la institución.
        </p>
    </div>

    <form class="mt-7 space-y-5" method="POST" action="<?php echo e(route('login.store')); ?>">
        <?php echo csrf_field(); ?>

        <label class="block">
            <span class="mb-2 block text-sm font-bold text-slate-700">Correo electrónico</span>
            <span class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.474a2 2 0 0 1-2.12 0L2.25 6.75"/></svg>
                </span>
                <input
                    class="min-h-12 w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-brand-600 focus:ring-4 focus:ring-brand-100"
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="nombre@correo.com"
                    maxlength="255"
                    autocomplete="email"
                    aria-invalid="<?php echo e($errors->has('email') ? 'true' : 'false'); ?>"
                    required
                    autofocus
                >
            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-700" role="alert">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span><?php echo e($message); ?>

                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-bold text-slate-700">Contraseña</span>
            <span class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 10.5h10.5A2.25 2.25 0 0 0 19.5 18.75v-6A2.25 2.25 0 0 0 17.25 10.5H6.75A2.25 2.25 0 0 0 4.5 12.75v6A2.25 2.25 0 0 0 6.75 21Z"/></svg>
                </span>
                <input
                    class="min-h-12 w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-brand-600 focus:ring-4 focus:ring-brand-100"
                    type="password"
                    name="password"
                    placeholder="Tu contraseña"
                    maxlength="255"
                    autocomplete="current-password"
                    aria-invalid="<?php echo e($errors->has('password') ? 'true' : 'false'); ?>"
                    required
                >
            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="mt-2 block text-sm font-medium text-red-700" role="alert"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>

        <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-slate-600">
            <input class="h-4 w-4 rounded border-slate-300 text-brand-700 focus:ring-brand-600" type="checkbox" name="remember" value="1">
            Mantener la sesión iniciada
        </label>

        <button class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-brand-700 px-4 py-3 font-bold text-white shadow-lg shadow-brand-700/20 transition hover:-translate-y-0.5 hover:bg-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-200" type="submit">
            Ingresar a mi cuenta
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
        </button>
    </form>

    <div class="my-6 flex items-center gap-4">
        <div class="h-px flex-1 bg-slate-200"></div>
        <span class="text-[0.68rem] font-bold uppercase tracking-[0.16em] text-slate-400">o inicia sesión con</span>
        <div class="h-px flex-1 bg-slate-200"></div>
    </div>

    <div>
        <a class="inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-100" href="<?php echo e(route('oauth.microsoft.redirect')); ?>">
            <svg class="h-5 w-5" viewBox="0 0 21 21" aria-hidden="true">
                <path fill="#f25022" d="M1 1h9v9H1z"/>
                <path fill="#7fba00" d="M11 1h9v9h-9z"/>
                <path fill="#00a4ef" d="M1 11h9v9H1z"/>
                <path fill="#ffb900" d="M11 11h9v9h-9z"/>
            </svg>
            Microsoft
        </a>
    </div>

    <p class="mt-6 text-center text-xs leading-5 text-slate-500">
        Si no puedes acceder, solicita apoyo al administrador de la plataforma.
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projects\plataforma-formacion\resources\views\auth\login.blade.php ENDPATH**/ ?>