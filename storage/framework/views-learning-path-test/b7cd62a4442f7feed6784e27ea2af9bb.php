<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['badge', 'size' => 'md']));

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

foreach (array_filter((['badge', 'size' => 'md']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $course = $badge->activity;
    $learningPath = $badge->learningPath;
    $source = $learningPath ?? $course;
    $identity = $source?->public_id ?? $source?->slug ?? $source?->id ?? $source?->name ?? $badge->name;
    $variant = abs(crc32((string) $identity));
    $palettes = [
        ['#7c3aed', '#2563eb', '#ede9fe'], ['#0891b2', '#0f766e', '#cffafe'],
        ['#ea580c', '#dc2626', '#ffedd5'], ['#059669', '#65a30d', '#d1fae5'],
        ['#db2777', '#9333ea', '#fce7f3'], ['#ca8a04', '#b45309', '#fef3c7'],
        ['#4f46e5', '#0891b2', '#e0e7ff'], ['#be123c', '#7e22ce', '#ffe4e6'],
    ];
    [$from, $to, $soft] = $palettes[$variant % count($palettes)];
    $shapes = ['50%', '28%', '50% 36% 50% 36%', '32% 50% 32% 50%'];
    $shape = $shapes[($variant >> 3) % count($shapes)];
    $symbols = ['✦', '◆', '▲', '✺', '⬢', '●', '✧', '■'];
    $symbol = $symbols[($variant >> 5) % count($symbols)];
    $words = preg_split('/\s+/u', trim($source?->name ?? $badge->name), -1, PREG_SPLIT_NO_EMPTY);
    $initials = collect($words)->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
    $coverFile = $course?->coverFile ?? $course?->trainingProgram?->coverFile;
    $dimensions = $size === 'lg' ? 'h-40 w-40' : 'h-28 w-28';
    $textSize = $size === 'lg' ? 'text-4xl' : 'text-2xl';
?>

<div <?php echo e($attributes->class(["relative mx-auto flex {$dimensions} items-center justify-center p-2 shadow-xl"])); ?>

    style="border-radius: <?php echo e($shape); ?>; background: linear-gradient(135deg, <?php echo e($from); ?>, <?php echo e($to); ?>); box-shadow: 0 16px 30px -15px <?php echo e($from); ?>;"
    title="Diseño de la ruta <?php echo e($source?->name ?? $badge->name); ?>">
    <div class="relative flex h-full w-full items-center justify-center overflow-hidden border-2 border-white/75" style="border-radius: <?php echo e($shape); ?>; background-color: <?php echo e($soft); ?>;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverFile): ?>
            <img class="absolute inset-0 h-full w-full object-cover" src="<?php echo e($coverFile->optimizedImageUrl(420, 420)); ?>" alt="Portada de <?php echo e($course->name); ?>">
            <span class="absolute inset-0" style="background: linear-gradient(145deg, <?php echo e($from); ?>99, <?php echo e($to); ?>cc);"></span>
        <?php else: ?>
            <span class="absolute -right-5 -top-5 h-20 w-20 rounded-full border-[12px] border-white/20"></span>
            <span class="absolute -bottom-5 -left-5 h-16 w-16 rotate-45 border-[10px] border-white/15"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <span class="relative flex flex-col items-center text-white drop-shadow-md">
            <span class="<?php echo e($textSize); ?> font-black leading-none"><?php echo e($symbol); ?></span>
            <span class="mt-1 text-xs font-extrabold tracking-[0.18em]"><?php echo e($initials); ?></span>
        </span>
    </div>
</div>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\course-badge-emblem.blade.php ENDPATH**/ ?>