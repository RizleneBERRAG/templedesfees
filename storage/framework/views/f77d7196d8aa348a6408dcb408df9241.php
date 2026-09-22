<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['chaton']));

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

foreach (array_filter((['chaton']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a class="fiche <?php if($chaton->statut === \App\Enums\KittenStatus::Adopte): ?> gone <?php endif; ?>"
   href="<?php echo e(route('kittens.show', $chaton)); ?>">
    <span class="ph">
        <?php if (isset($component)) { $__componentOriginal3e43da63772e725970863e9067088b49 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3e43da63772e725970863e9067088b49 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chip','data' => ['statut' => $chaton->statut]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('chip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['statut' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chaton->statut)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3e43da63772e725970863e9067088b49)): ?>
<?php $attributes = $__attributesOriginal3e43da63772e725970863e9067088b49; ?>
<?php unset($__attributesOriginal3e43da63772e725970863e9067088b49); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3e43da63772e725970863e9067088b49)): ?>
<?php $component = $__componentOriginal3e43da63772e725970863e9067088b49; ?>
<?php unset($__componentOriginal3e43da63772e725970863e9067088b49); ?>
<?php endif; ?>
        <img src="<?php echo e(asset($chaton->photo_principale)); ?>"
             alt="<?php echo e($chaton->nom); ?>, chaton Maine Coon <?php echo e(\Illuminate\Support\Str::lower($chaton->robe)); ?>"
             loading="lazy" width="900" height="1200">
    </span>
    <span class="bd">
        <span class="nm"><h3><?php echo e($chaton->nom); ?></h3><span class="ref"><?php echo e($chaton->reference); ?></span></span>
        <dl>
            <dt>Sexe</dt><dd><?php echo e(\Illuminate\Support\Str::ucfirst($chaton->sexe)); ?></dd>
            <dt>Robe</dt><dd><?php echo e($chaton->robe); ?></dd>
            <dt>Né le</dt><dd><?php echo e($chaton->litter->date_naissance->translatedFormat('j F Y')); ?></dd>
        </dl>
        <span class="go">Voir la fiche complète →</span>
    </span>
</a>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/components/kitten-card.blade.php ENDPATH**/ ?>