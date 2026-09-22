<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['taille' => 'moyen']));

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

foreach (array_filter((['taille' => 'moyen']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<?php
    $mesures = [
        'petit'  => ['w' => 40,  'h' => 14, 'vb' => '0 0 40 14'],
        'moyen'  => ['w' => 96,  'h' => 26, 'vb' => '0 0 96 26'],
        'grand'  => ['w' => 150, 'h' => 30, 'vb' => '0 0 150 30'],
    ];
    $m = $mesures[$taille] ?? $mesures['moyen'];
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($taille === 'petit'): ?>
    <svg <?php echo e($attributes); ?> width="<?php echo e($m['w']); ?>" height="<?php echo e($m['h']); ?>" viewBox="<?php echo e($m['vb']); ?>"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M20 2c-4 3-4 7 0 10 4-3 4-7 0-10Z" />
        <path d="M14 7H2M38 7H26" />
    </svg>
<?php elseif($taille === 'grand'): ?>
    <svg <?php echo e($attributes); ?> width="<?php echo e($m['w']); ?>" height="<?php echo e($m['h']); ?>" viewBox="<?php echo e($m['vb']); ?>"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M75 6c-6 5-6 13 0 18 6-5 6-13 0-18Z" />
        <circle cx="75" cy="15" r="2.4" fill="currentColor" stroke="none" />
        <path d="M63 15c-8 0-12-7-20 0 8 7 12 0 20 0Z" />
        <path d="M87 15c8 0 12-7 20 0-8 7-12 0-20 0Z" />
        <path d="M35 15H6M144 15h-29" />
        <circle cx="39" cy="15" r="1.8" />
        <circle cx="111" cy="15" r="1.8" />
    </svg>
<?php else: ?>
    <svg <?php echo e($attributes); ?> width="<?php echo e($m['w']); ?>" height="<?php echo e($m['h']); ?>" viewBox="<?php echo e($m['vb']); ?>"
         fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
        <path d="M48 3C48 9 44 12 40 13c4 1 8 4 8 10 0-6 4-9 8-10-4-1-8-4-8-10Z" />
        <path d="M34 13c-6 0-10-4-14 0 4 4 8 0 14 0Z" />
        <path d="M62 13c6 0 10-4 14 0-4 4-8 0-14 0Z" />
        <path d="M14 13H2M94 13H82" />
    </svg>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/components/fleuron.blade.php ENDPATH**/ ?>