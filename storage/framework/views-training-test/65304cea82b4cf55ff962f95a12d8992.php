<footer class="border-t-4 border-brand-500 bg-brand-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-9 px-4 py-10 sm:px-6 md:grid-cols-[1.35fr_0.8fr_0.8fr] md:gap-8 lg:px-8 lg:py-12">
        <div class="mx-auto flex max-w-md flex-col items-center text-center md:mx-0 md:items-start md:text-left">
            <img class="h-16 w-auto sm:h-20" src="<?php echo e(asset('img/LogoUNAQ.webp')); ?>" alt="Universidad Aeronáutica en Querétaro">
            <p class="mt-5 text-sm leading-6 text-blue-100/75">Un espacio para descubrir oportunidades, continuar aprendiendo y reconocer cada logro.</p>
        </div>
        <div class="grid grid-cols-2 gap-5 text-center md:contents md:text-left">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-200 sm:text-sm">Plataforma</p>
                <nav class="mt-4 grid justify-items-center gap-3 text-sm text-blue-100/75 md:justify-items-start" aria-label="Enlaces del pie de página">
                    <a class="w-fit transition hover:text-white" href="<?php echo e(route('home')); ?>">Inicio</a>
                    <a class="w-fit transition hover:text-white" href="<?php echo e(route('home')); ?>#beneficios">Beneficios</a>
                    <a class="w-fit transition hover:text-white" href="<?php echo e(route('home')); ?>#funciones">Conoce más</a>
                </nav>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-200 sm:text-sm">Acceso</p>
                <p class="mt-4 text-sm leading-6 text-blue-100/75">Ingresa con las credenciales asignadas a tu perfil institucional.</p>
                <a class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white transition hover:text-blue-200" href="<?php echo e(route('login')); ?>">Iniciar sesión <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-center text-xs text-blue-100/60 sm:px-6 md:flex-row md:items-center md:justify-between md:text-left lg:px-8">
            <p>© <?php echo e(date('Y')); ?> Universidad Aeronáutica en Querétaro.</p>
            <p>Plataforma Institucional de Formación y Microcredenciales</p>
        </div>
    </div>
</footer>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\public-footer.blade.php ENDPATH**/ ?>