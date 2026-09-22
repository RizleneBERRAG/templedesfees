

<?php $__env->startSection('title', "Cette page n'existe pas"); ?>
<?php $__env->startSection('description', "La page demandée n'existe pas ou plus sur le site de l'élevage Chatterie du Temple des Fées."); ?>

<?php $__env->startPush('head'); ?>
    <meta name="robots" content="noindex">
<?php $__env->stopPush(); ?>

<?php
    // Les anciennes adresses exactes sont redirigées en 301 par routes/web.php et
    // n'arrivent jamais ici. Restent les adresses PLUS PROFONDES du même site —
    // /reproducteurs-2/uzumaki, par exemple — qu'aucune redirection exacte ne
    // couvre. On suggère alors la bonne rubrique d'après le premier segment,
    // plutôt que de laisser le visiteur dans une impasse.
    $premier    = explode('/', trim(request()->path(), '/'))[0] ?? '';
    $suggestion = config('chatterie.anciennes_urls')[$premier] ?? null;
?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap" style="max-width:760px">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Erreur 404','titre' => 'Cette page n’existe pas','lede' => 'Le lien est peut-être ancien, ou l’adresse comporte une erreur. Voici par où reprendre.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Erreur 404','titre' => 'Cette page n’existe pas','lede' => 'Le lien est peut-être ancien, ou l’adresse comporte une erreur. Voici par où reprendre.']); ?>
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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($suggestion): ?>
            <p class="flash" style="margin-bottom:30px">
                Vous cherchiez sans doute
                <a href="<?php echo e(route($suggestion)); ?>">cette page</a> — l’adresse a changé depuis l’ancien site.
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="cells">
            <a class="cell-b" href="<?php echo e(route('kittens.index')); ?>" style="text-decoration:none">
                <span class="n">Nos chatons</span>
                <p>La portée en cours, les fiches détaillées et les disponibilités.</p>
            </a>
            <a class="cell-b" href="<?php echo e(route('cats.index')); ?>" style="text-decoration:none">
                <span class="n">L’élevage</span>
                <p>Les reproducteurs, leurs pedigrees et leurs dépistages.</p>
            </a>
            <a class="cell-b" href="<?php echo e(route('adoption.create')); ?>" style="text-decoration:none">
                <span class="n">Adopter</span>
                <p>Le parcours en quatre étapes et la demande de pré-réservation.</p>
            </a>
            <a class="cell-b" href="<?php echo e(route('contact')); ?>" style="text-decoration:none">
                <span class="n">Nous joindre</span>
                <p>Par téléphone, par email, ou en venant nous voir sur rendez-vous.</p>
            </a>
        </div>

        <div class="btnrow" style="margin-top:34px">
            <a class="btn" href="<?php echo e(route('home')); ?>">Retour à l’accueil</a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/errors/404.blade.php ENDPATH**/ ?>