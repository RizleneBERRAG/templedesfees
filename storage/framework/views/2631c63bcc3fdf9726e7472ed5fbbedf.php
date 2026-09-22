

<?php $__env->startSection('title', "L'élevage et nos reproducteurs"); ?>
<?php $__env->startSection('description', "Chatterie du Temple des Fées, élevage familial déclaré à la chambre d'agriculture à Lapeyrouse-Mornay. Nos reproducteurs, leur robe, leur pedigree et leurs dépistages."); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'L\'élevage','titre' => 'Chatterie du Temple des Fées','lede' => 'Un élevage familial déclaré à la chambre d\'agriculture, titulaire du certificat de capacité, installé à Lapeyrouse-Mornay, à vingt minutes de Lyon.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'L\'élevage','titre' => 'Chatterie du Temple des Fées','lede' => 'Un élevage familial déclaré à la chambre d\'agriculture, titulaire du certificat de capacité, installé à Lapeyrouse-Mornay, à vingt minutes de Lyon.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $attributes = $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $component = $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>

        <div class="two off">
            <figure class="figure">
                <img src="<?php echo e(asset('images/cats/couple.webp')); ?>" alt="Deux Maine Coon de la chatterie">
                <figcaption>Uzumaki et Uanna</figcaption>
            </figure>
            <div class="stack">
                <h3>Deux adultes, une portée ou deux par an</h3>
                <p class="lede">
                    Nous ne faisons pas de volume. Le rythme des portées est calé sur la récupération
                    des femelles, jamais sur la demande. Entre deux portées, la maison redevient une
                    maison : quatre chats, deux enfants, un chien.
                </p>
                <p class="lede">
                    C'est ce qui explique que nos chatons arrivent chez vous déjà propres, habitués aux
                    bruits, et demandeurs de contact — ils n'ont jamais connu autre chose.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="band ink2">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'La lignée','titre' => 'Nos reproducteurs','lede' => 'Chaque chat a sa fiche complète : robe, pedigree, identification et résultats de dépistage.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'La lignée','titre' => 'Nos reproducteurs','lede' => 'Chaque chat a sa fiche complète : robe, pedigree, identification et résultats de dépistage.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $attributes = $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $component = $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>
        <div class="repros">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal2b14af9ad13f574c518b459e5d740aba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b14af9ad13f574c518b459e5d740aba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cat-card','data' => ['chat' => $chat]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chat)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b14af9ad13f574c518b459e5d740aba)): ?>
<?php $attributes = $__attributesOriginal2b14af9ad13f574c518b459e5d740aba; ?>
<?php unset($__attributesOriginal2b14af9ad13f574c518b459e5d740aba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b14af9ad13f574c518b459e5d740aba)): ?>
<?php $component = $__componentOriginal2b14af9ad13f574c518b459e5d740aba; ?>
<?php unset($__componentOriginal2b14af9ad13f574c518b459e5d740aba); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginal474f5b35d7f6ada5a194626b31e0203c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal474f5b35d7f6ada5a194626b31e0203c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-band','data' => ['image' => 'images/cats/wild.webp','legende' => 'Une à deux portées par an, pas davantage','hauteur' => '44vh']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => 'images/cats/wild.webp','legende' => 'Une à deux portées par an, pas davantage','hauteur' => '44vh']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal474f5b35d7f6ada5a194626b31e0203c)): ?>
<?php $attributes = $__attributesOriginal474f5b35d7f6ada5a194626b31e0203c; ?>
<?php unset($__attributesOriginal474f5b35d7f6ada5a194626b31e0203c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal474f5b35d7f6ada5a194626b31e0203c)): ?>
<?php $component = $__componentOriginal474f5b35d7f6ada5a194626b31e0203c; ?>
<?php unset($__componentOriginal474f5b35d7f6ada5a194626b31e0203c); ?>
<?php endif; ?>

<section class="band paper">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'En clair','titre' => 'L\'élevage en quatre chiffres']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'En clair','titre' => 'L\'élevage en quatre chiffres']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $attributes = $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d)): ?>
<?php $component = $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d; ?>
<?php unset($__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d); ?>
<?php endif; ?>
        <div class="facts">
            <div class="fact"><b data-count="2">0</b><span>Portées par an maximum</span></div>
            <div class="fact"><b data-count="<?php echo e(\App\Models\Litter::SEMAINES_AVANT_CESSION); ?>">0</b><span>Semaines minimum avant départ</span></div>
            <div class="fact"><b data-count="2">0</b><span>Visites avant réservation</span></div>
            <div class="fact"><b data-count="4">0</b><span>Dépistages par reproducteur</span></div>
        </div>
        <div class="btnrow" style="margin-top:36px">
            <a class="btn" href="<?php echo e(route('kittens.index')); ?>">Voir les chatons</a>
            <a class="btn ghost" href="<?php echo e(route('contact')); ?>">Venir nous voir</a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/cats/index.blade.php ENDPATH**/ ?>