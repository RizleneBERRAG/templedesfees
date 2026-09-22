<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['chat']));

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

foreach (array_filter((['chat']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a class="repro" href="<?php echo e(route('cats.show', $chat)); ?>">
    <span class="im">
        <img src="<?php echo e(asset($chat->photo_principale)); ?>"
             alt="<?php echo e($chat->nom); ?>, Maine Coon <?php echo e(\Illuminate\Support\Str::lower($chat->robe)); ?>"
             loading="lazy" width="1100" height="1467">
    </span>
    <span class="cap">
        <span class="mono"><?php echo e($chat->role->libelle()); ?></span>
        <h3><?php echo e($chat->nom); ?></h3>
        <p><?php echo e($chat->robe); ?> · <?php echo e(\Illuminate\Support\Str::ucfirst($chat->sexe)); ?> · <?php echo e($chat->annee_naissance); ?></p>
    </span>
</a>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/components/cat-card.blade.php ENDPATH**/ ?>