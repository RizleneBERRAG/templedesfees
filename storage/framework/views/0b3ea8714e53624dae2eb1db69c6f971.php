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

<?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['class' => 'tests','titre' => 'Dépistages — '.e($chat->nom).'','note' => 'Chaque résultat est saisi avec sa date, son laboratoire et le compte-rendu en PDF, consultable par les familles.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'tests','titre' => 'Dépistages — '.e($chat->nom).'','note' => 'Chaque résultat est saisi avec sa date, son laboratoire et le compte-rendu en PDF, consultable par les familles.']); ?>
    <table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $chat->healthTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <th><?php echo e($test->type->libelle()); ?></th>
                <td>
                    <b <?php if($test->estEnAttente()): ?> style="color:var(--bronze-lt)" <?php endif; ?>><?php echo e($test->resultat ?? 'À programmer'); ?></b><br>
                    <span class="small" style="font-size:.79rem"><?php echo e($test->commentaire ?? $test->type->methode()); ?></span>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="2" class="todo">Aucun dépistage saisi pour l'instant.</td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </table>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2)): ?>
<?php $attributes = $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2; ?>
<?php unset($__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2)): ?>
<?php $component = $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2; ?>
<?php unset($__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/components/health-table.blade.php ENDPATH**/ ?>