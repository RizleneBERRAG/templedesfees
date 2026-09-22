<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['photos']));

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

foreach (array_filter((['photos']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    /* Les fiches n'ont pas toutes plusieurs photos. Avec une seule, on rend
       exactement ce que rendait la fiche avant la visionneuse : un cadre, une
       image. Pas de ruban vide, pas de compteur « 1 / 1 ». */
    $photos = collect($photos)->values();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photos->count() < 2): ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photos->isNotEmpty()): ?>
        <div class="photo">
            <img src="<?php echo e(asset($photos[0]['chemin'])); ?>" alt="<?php echo e($photos[0]['alt']); ?>">
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php else: ?>

    <div class="viewer" data-lightbox>
        
        <div class="photo viewer-scene" data-zoom role="button" tabindex="0"
             aria-label="Agrandir la photo">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e(asset($photo['chemin'])); ?>" alt="<?php echo e($photo['alt']); ?>"
                     class="<?php echo \Illuminate\Support\Arr::toCssClasses(['visible' => $i === 0]); ?>"
                     <?php if($i > 0): ?> loading="lazy" <?php endif; ?>>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <span class="viewer-compteur"><b>1</b>&thinsp;/&thinsp;<?php echo e($photos->count()); ?></span>
            <span class="viewer-zoom">Agrandir</span>
        </div>

        
        
        <div class="viewer-rail" role="group" aria-label="Photos de la fiche">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="viewer-vignette"
                        <?php if($i === 0): ?> aria-current="true" <?php endif; ?>
                        tabindex="<?php echo e($i === 0 ? '0' : '-1'); ?>"
                        data-full="<?php echo e(asset($photo['chemin'])); ?>"
                        data-legende="<?php echo e($photo['legende'] ?: $photo['alt']); ?>">
                    <img src="<?php echo e(asset($photo['chemin'])); ?>" alt="Photo <?php echo e($i + 1); ?> — <?php echo e($photo['alt']); ?>"
                         loading="lazy" width="150" height="150">
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/components/photo-viewer.blade.php ENDPATH**/ ?>