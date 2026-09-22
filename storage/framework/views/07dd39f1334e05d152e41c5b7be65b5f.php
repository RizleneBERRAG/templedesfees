

<?php $__env->startSection('title', "Questions fréquentes sur l'adoption d'un Maine Coon"); ?>
<?php $__env->startSection('description', "Âge de départ, tarif, compatibilité avec les enfants et les chiens, vie en appartement, LOOF, allergies : les réponses complètes avant d'adopter un Maine Coon."); ?>

<?php $__env->startPush('schema'); ?>

<?php
    $schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($f) => [
        '@type' => 'Question',
        'name'  => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->reponse)],
    ])->values(),
];
?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<section class="band paper">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Questions fréquentes','titre' => 'Ce qu\'on nous demande le plus','lede' => 'Les réponses complètes, y compris celles qui pourraient vous faire renoncer. On préfère que vous renonciez avant qu\'après.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Questions fréquentes','titre' => 'Ce qu\'on nous demande le plus','lede' => 'Les réponses complètes, y compris celles qui pourraient vous faire renoncer. On préfère que vous renonciez avant qu\'après.']); ?>
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

        <div class="faqwrap">
            <div>
                <div class="faq">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <details <?php if($loop->first): ?> open <?php endif; ?>>
                            <summary><?php echo e($faq->question); ?></summary>
                            <div class="ans"><?php echo $faq->reponse; ?></div>
                        </details>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="btnrow" style="margin-top:40px">
                    <a class="btn" href="<?php echo e(route('adoption.create')); ?>">Poser une autre question</a>
                    <a class="btn ghost" href="tel:+33624488936"><?php echo e(\App\Models\Setting::get('contact.telephone')); ?></a>
                </div>
            </div>
            <aside class="aside">
                <?php if (isset($component)) { $__componentOriginal03a484c299c9f8e29be2dedc42b714ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03a484c299c9f8e29be2dedc42b714ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rosettes','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rosettes'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03a484c299c9f8e29be2dedc42b714ae)): ?>
<?php $attributes = $__attributesOriginal03a484c299c9f8e29be2dedc42b714ae; ?>
<?php unset($__attributesOriginal03a484c299c9f8e29be2dedc42b714ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03a484c299c9f8e29be2dedc42b714ae)): ?>
<?php $component = $__componentOriginal03a484c299c9f8e29be2dedc42b714ae; ?>
<?php unset($__componentOriginal03a484c299c9f8e29be2dedc42b714ae); ?>
<?php endif; ?>
                <h4>Votre question n'y est pas ?</h4>
                <p>Appelez-nous. On répond plus volontiers au téléphone qu'en trois lignes, surtout quand il s'agit de savoir si un Maine Coon est fait pour vous.</p>
                <a class="btn" href="tel:+33624488936" style="justify-content:center"><?php echo e(\App\Models\Setting::get('contact.telephone')); ?></a>
                <a class="tlink" href="<?php echo e(route('adoption.create')); ?>">Demander une visite</a>
            </aside>
        </div>
    </div>
</section>

<div class="band tight" style="padding-block:clamp(22px,3vw,36px)">
    <?php if (isset($component)) { $__componentOriginal88b2be7e5b1343afa0d4e3e348532adf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-strip','data' => ['titre' => 'L\'élevage en images']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'L\'élevage en images']); ?>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/faq.blade.php ENDPATH**/ ?>