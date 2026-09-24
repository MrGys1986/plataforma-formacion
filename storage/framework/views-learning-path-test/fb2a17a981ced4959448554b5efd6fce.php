<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name' => 'squares']));

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

foreach (array_filter((['name' => 'squares']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<svg <?php echo e($attributes->class('h-5 w-5')); ?> fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($name):
        case ('home'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="m3 11.25 9-7.5 9 7.5M5.25 9.5v10.75h13.5V9.5M9 20.25v-6h6v6"/>
            <?php break; ?>
        <?php case ('catalog'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25A2.25 2.25 0 0 1 6.75 3h4.5v16.5h-4.5A2.25 2.25 0 0 0 4.5 21.75V5.25Zm15 0A2.25 2.25 0 0 0 17.25 3h-4.5v16.5h4.5a2.25 2.25 0 0 1 2.25 2.25V5.25Z"/>
            <?php break; ?>
        <?php case ('courses'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25 12 3l9 5.25L12 13.5 3 8.25Zm3 2.25V15c0 1.66 2.69 3 6 3s6-1.34 6-3v-4.5M21 9v6"/>
            <?php break; ?>
        <?php case ('payment'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5A1.75 1.75 0 0 1 22 8.5v9a1.75 1.75 0 0 1-1.75 1.75H3.75A1.75 1.75 0 0 1 2 17.5v-9a1.75 1.75 0 0 1 1.75-1.75ZM2 10.5h20M6 15.5h4"/>
            <?php break; ?>
        <?php case ('teaching'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4.5h16v11H4v-11Zm4 15h8M12 15.5v4M8 9h3m-3 3h8"/>
            <?php break; ?>
        <?php case ('path'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Zm14 8a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5ZM7.5 8h4a3 3 0 0 1 3 3v2a3 3 0 0 0 3 3h-1"/>
            <?php break; ?>
        <?php case ('certificate'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h10A2.25 2.25 0 0 1 19.25 6v8A2.25 2.25 0 0 1 17 16.25H7A2.25 2.25 0 0 1 4.75 14V6A2.25 2.25 0 0 1 7 3.75ZM9 8h6m-6 3h4m-3 5.25L9 21l3-1.75L15 21l-1-4.75"/>
            <?php break; ?>
        <?php case ('video'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.25h10.5A1.75 1.75 0 0 1 17 8v8a1.75 1.75 0 0 1-1.75 1.75H4.75A1.75 1.75 0 0 1 3 16V8a1.75 1.75 0 0 1 1.75-1.75ZM17 10l4-2v8l-4-2"/>
            <?php break; ?>
        <?php case ('users'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.25v-1.5A3.75 3.75 0 0 0 11.25 14h-4.5A3.75 3.75 0 0 0 3 17.75v1.5M9 10.25a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5Zm7.5.5a2.75 2.75 0 1 0 0-5.5m1 8.75a3.5 3.5 0 0 1 3.5 3.5v1.75"/>
            <?php break; ?>
        <?php case ('evidence'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3h7.5L19 7.75V21H6.75A1.75 1.75 0 0 1 5 19.25V4.75A1.75 1.75 0 0 1 6.75 3Zm7.5 0v4.75H19M8.5 12h7m-7 3h7m-7 3h4"/>
            <?php break; ?>
        <?php case ('evaluation'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8m-6-1h4a1 1 0 0 1 1 1v1H9V4a1 1 0 0 1 1-1ZM6 5h12a2 2 0 0 1 2 2v13H4V7a2 2 0 0 1 2-2Zm2 7 2.25 2.25L16 8.5"/>
            <?php break; ?>
        <?php case ('report'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10m5 10V4m6 16v-7m5 7V7"/>
            <?php break; ?>
        <?php case ('settings'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm7.4 3.5 1.35-1.1-1.5-2.6-1.65.6a7 7 0 0 0-1.5-.9l-.3-1.75h-3l-.3 1.75a7 7 0 0 0-1.5.9l-1.65-.6-1.5 2.6L8.6 12a7 7 0 0 0 0 1.75l-1.35 1.1 1.5 2.6 1.65-.6a7 7 0 0 0 1.5.9l.3 1.75h3l.3-1.75a7 7 0 0 0 1.5-.9l1.65.6 1.5-2.6-1.35-1.1a7 7 0 0 0 0-1.75Z"/>
            <?php break; ?>
        <?php default: ?>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6.5v6.5H4V4Zm9.5 0H20v6.5h-6.5V4ZM4 13.5h6.5V20H4v-6.5Zm9.5 0H20V20h-6.5v-6.5Z"/>
    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</svg>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\components\portal-nav-icon.blade.php ENDPATH**/ ?>