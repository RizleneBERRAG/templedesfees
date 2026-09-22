

<?php $__env->startSection('title', "Chatons Maine Coon disponibles"); ?>
<?php $__env->startSection('description', "Les chatons Maine Coon de la portée en cours : robe, sexe, poids, suivi vétérinaire et statut mis à jour. Inscrits au LOOF, cédés identifiés et vaccinés."); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Nos chatons','titre' => ''.e($portee->code).' — '.e($portee->pere?->nom).' × '.e($portee->mere?->nom).'','lede' => 'Nés le '.e($portee->date_naissance->translatedFormat('j F Y')).'. '.e($portee->phraseDisponibilite() ? \Illuminate\Support\Str::ucfirst($portee->phraseDisponibilite()).', ' : '').'identifiés, vaccinés, vermifugés et inscrits au LOOF.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Nos chatons','titre' => ''.e($portee->code).' — '.e($portee->pere?->nom).' × '.e($portee->mere?->nom).'','lede' => 'Nés le '.e($portee->date_naissance->translatedFormat('j F Y')).'. '.e($portee->phraseDisponibilite() ? \Illuminate\Support\Str::ucfirst($portee->phraseDisponibilite()).', ' : '').'identifiés, vaccinés, vermifugés et inscrits au LOOF.']); ?>
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

        <div class="filters">
            <a href="<?php echo e(route('kittens.index')); ?>"
               class="<?php echo \Illuminate\Support\Arr::toCssClasses(['btn-filter']); ?>" aria-pressed="<?php echo e($statut ? 'false' : 'true'); ?>"
               role="button">Tous (<?php echo e($total); ?>)</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Enums\KittenStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('kittens.index', ['statut' => $cas->value])); ?>"
                   aria-pressed="<?php echo e($statut === $cas->value ? 'true' : 'false'); ?>"
                   role="button"><?php echo e($cas->libelle()); ?>s (<?php echo e($filtres[$cas->value] ?? 0); ?>)</a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $chatons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chaton): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginal3acce698029e71fd2ba6d4d82db74a41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3acce698029e71fd2ba6d4d82db74a41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kitten-card','data' => ['chaton' => $chaton]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kitten-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chaton' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chaton)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3acce698029e71fd2ba6d4d82db74a41)): ?>
<?php $attributes = $__attributesOriginal3acce698029e71fd2ba6d4d82db74a41; ?>
<?php unset($__attributesOriginal3acce698029e71fd2ba6d4d82db74a41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3acce698029e71fd2ba6d4d82db74a41)): ?>
<?php $component = $__componentOriginal3acce698029e71fd2ba6d4d82db74a41; ?>
<?php unset($__componentOriginal3acce698029e71fd2ba6d4d82db74a41); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="lede">
                    Aucun chaton dans cette catégorie pour le moment.
                    <a class="tlink" href="<?php echo e(route('adoption.create')); ?>">Rejoindre la liste d'attente</a>
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<div class="band tight" style="padding-block:clamp(22px,3vw,36px)">
    <?php if (isset($component)) { $__componentOriginal88b2be7e5b1343afa0d4e3e348532adf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-strip','data' => ['titre' => 'Les chatons au fil des semaines']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Les chatons au fil des semaines']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf)): ?>
<?php $attributes = $__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf; ?>
<?php unset($__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal88b2be7e5b1343afa0d4e3e348532adf)): ?>
<?php $component = $__componentOriginal88b2be7e5b1343afa0d4e3e348532adf; ?>
<?php unset($__componentOriginal88b2be7e5b1343afa0d4e3e348532adf); ?>
<?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee->events->isNotEmpty()): ?>
<section class="band ink2">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Suivi de la portée','titre' => 'Où en sont-ils aujourd\'hui','lede' => 'Le même calendrier pour les '.e($portee->nb_chatons).' chatons. Il se remplit au fil des actes vétérinaires saisis dans l\'espace de gestion.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Suivi de la portée','titre' => 'Où en sont-ils aujourd\'hui','lede' => 'Le même calendrier pour les '.e($portee->nb_chatons).' chatons. Il se remplit au fil des actes vétérinaires saisis dans l\'espace de gestion.']); ?>
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
        <div class="record"><?php if (isset($component)) { $__componentOriginal93f2afea2d7941ca7799292711b7f46f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f2afea2d7941ca7799292711b7f46f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.timeline','data' => ['events' => $portee->events]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['events' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portee->events)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93f2afea2d7941ca7799292711b7f46f)): ?>
<?php $attributes = $__attributesOriginal93f2afea2d7941ca7799292711b7f46f; ?>
<?php unset($__attributesOriginal93f2afea2d7941ca7799292711b7f46f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93f2afea2d7941ca7799292711b7f46f)): ?>
<?php $component = $__componentOriginal93f2afea2d7941ca7799292711b7f46f; ?>
<?php unset($__componentOriginal93f2afea2d7941ca7799292711b7f46f); ?>
<?php endif; ?></div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($archives->isNotEmpty()): ?>
<section class="band paper">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Historique','titre' => 'Les portées précédentes','lede' => 'Les portées passées restent en ligne. C\'est la meilleure preuve du sérieux d\'un élevage : on voit ce que sont devenus les chatons.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Historique','titre' => 'Les portées précédentes','lede' => 'Les portées passées restent en ligne. C\'est la meilleure preuve du sérieux d\'un élevage : on voit ce que sont devenus les chatons.']); ?>
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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $archives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="two" <?php if(! $loop->first): ?> style="margin-top:40px" <?php endif; ?>>
                <figure class="figure">
                    <img src="<?php echo e(asset($archive->photo_principale ?? 'images/cats/portee.webp')); ?>"
                         alt="<?php echo e($archive->code); ?>, chatons Maine Coon" loading="lazy">
                    <figcaption><?php echo e($archive->code); ?> — <?php echo e($archive->date_naissance->translatedFormat('F Y')); ?></figcaption>
                </figure>
                <div class="stack">
                    <p class="lede"><?php echo e($archive->description); ?></p>
                    <div class="facts">
                        <div class="fact"><b data-count="<?php echo e($archive->kittens_count); ?>">0</b><span>Chatons</span></div>
                        <div class="fact"><b data-count="<?php echo e($archive->kittens_count); ?>">0</b><span>Adoptés</span></div>
                        <div class="fact"><b data-count="0">0</b><span>Retour</span></div>
                    </div>
                    <p class="small">
                        Aucun nom de famille d'adoptant n'est publié sur ce site. Le statut d'un chaton
                        est une information sur le chaton, pas sur la personne qui l'a accueilli.
                    </p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/kittens/index.blade.php ENDPATH**/ ?>