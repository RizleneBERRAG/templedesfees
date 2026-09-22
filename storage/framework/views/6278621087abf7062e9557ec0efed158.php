

<?php $__env->startSection('title', $chaton->nom.' — chaton Maine Coon '.$chaton->statut->libelle()); ?>
<?php $__env->startSection('description', \Illuminate\Support\Str::limit(strip_tags($chaton->description), 150)); ?>
<?php $__env->startSection('og_image', asset($chaton->photo_principale)); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <div class="shead" style="margin-bottom:30px">
            <?php if (isset($component)) { $__componentOriginal03a484c299c9f8e29be2dedc42b714ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03a484c299c9f8e29be2dedc42b714ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rosettes','data' => ['class' => 'rosettes rail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('rosettes'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'rosettes rail']); ?>
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
            <div class="txt">
                <span class="eyebrow">
                    <a href="<?php echo e(route('kittens.index')); ?>" style="text-decoration:none">← <?php echo e($portee->code); ?></a>
                    · Fiche <?php echo e($chaton->reference); ?>

                </span>
                <h1 style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                    <?php echo e($chaton->nom); ?> <?php if (isset($component)) { $__componentOriginal3e43da63772e725970863e9067088b49 = $component; } ?>
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
                </h1>
                <p class="lede"><?php echo e($chaton->description); ?></p>
            </div>
        </div>

        <div class="detail">
            <div class="stack" style="gap:14px">
                <?php if (isset($component)) { $__componentOriginaleaab132dbe310803d7778e2c66aa2d41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleaab132dbe310803d7778e2c66aa2d41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-viewer','data' => ['photos' => $chaton->galerie()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-viewer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['photos' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chaton->galerie())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaleaab132dbe310803d7778e2c66aa2d41)): ?>
<?php $attributes = $__attributesOriginaleaab132dbe310803d7778e2c66aa2d41; ?>
<?php unset($__attributesOriginaleaab132dbe310803d7778e2c66aa2d41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaleaab132dbe310803d7778e2c66aa2d41)): ?>
<?php $component = $__componentOriginaleaab132dbe310803d7778e2c66aa2d41; ?>
<?php unset($__componentOriginaleaab132dbe310803d7778e2c66aa2d41); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fratrie->isNotEmpty()): ?>
                    <div class="grid" style="grid-template-columns:repeat(3,1fr);gap:12px">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fratrie->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a class="figure" style="margin:0" href="<?php echo e(route('kittens.show', $frere)); ?>">
                                <img src="<?php echo e(asset($frere->photo_principale)); ?>"
                                     alt="<?php echo e($frere->nom); ?>, de la même portée" loading="lazy" style="aspect-ratio:1">
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="stack" style="gap:22px">

                <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Identité','meta' => ''.e($chaton->reference).'','note' => 'Les numéros LOOF et ICAD se saisissent depuis l\'espace de gestion. Tant qu\'ils sont vides, la fiche reste en brouillon et n\'est pas publiée — c\'est la règle imposée par la réglementation sur les annonces de cession.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Identité','meta' => ''.e($chaton->reference).'','note' => 'Les numéros LOOF et ICAD se saisissent depuis l\'espace de gestion. Tant qu\'ils sont vides, la fiche reste en brouillon et n\'est pas publiée — c\'est la règle imposée par la réglementation sur les annonces de cession.']); ?>
                    <table>
                        <tr><th>Sexe</th><td><?php echo e(\Illuminate\Support\Str::ucfirst($chaton->sexe)); ?></td></tr>
                        <tr><th>Date de naissance</th><td><?php echo e($portee->date_naissance->translatedFormat('j F Y')); ?></td></tr>
                        <tr><th>Âge</th><td><?php echo e($chaton->ageEnSemaines()); ?> semaines</td></tr>
                        <tr><th>Robe</th><td><?php echo e($chaton->robe); ?></td></tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chaton->poidsFormate()): ?>
                            <tr><th>Poids au dernier contrôle</th><td style="font-variant-numeric:tabular-nums"><?php echo e($chaton->poidsFormate()); ?></td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <tr><th>Parents</th><td><?php echo e($portee->pere?->nom); ?> × <?php echo e($portee->mere?->nom); ?></td></tr>
                        <tr><th>Inscription</th><td>LOOF — pedigree remis au départ</td></tr>
                        <tr><th>N° de portée LOOF</th><td class="<?php echo \Illuminate\Support\Arr::toCssClasses(['todo' => blank($portee->loof_portee_numero)]); ?>"><?php echo e($portee->loof_portee_numero ?? 'À compléter'); ?></td></tr>
                        <tr><th>Identification ICAD</th><td class="<?php echo \Illuminate\Support\Arr::toCssClasses(['todo' => blank($chaton->icad_numero)]); ?>"><?php echo e($chaton->icad_numero ?? 'À compléter'); ?></td></tr>
                        <tr><th>Disponible à partir du</th><td><?php echo e($portee->date_disponibilite?->translatedFormat('j F Y')); ?></td></tr>
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

                <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Pedigree','meta' => '3 générations','note' => 'Les générations précédentes se reprennent du pedigree LOOF. Elles permettent d\'afficher le taux de consanguinité de la portée.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Pedigree','meta' => '3 générations','note' => 'Les générations précédentes se reprennent du pedigree LOOF. Elles permettent d\'afficher le taux de consanguinité de la portée.']); ?>
                    <div class="tree">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['Père', $portee->pere], ['Mère', $portee->mere]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$role, $parent]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="gen">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($parent): ?>
                                    <a class="cell" href="<?php echo e(route('cats.show', $parent)); ?>">
                                        <em><?php echo e($role); ?></em>
                                        <strong><?php echo e($parent->nom); ?></strong>
                                        <span><?php echo e($parent->robe); ?></span>
                                    </a>
                                <?php else: ?>
                                    <div class="cell empty"><em><?php echo e($role); ?></em><strong>À compléter</strong></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="sub">
                                    <div class="cell empty"><em>Grand-père <?php echo e($role === 'Père' ? 'paternel' : 'maternel'); ?></em><strong>À compléter</strong></div>
                                    <div class="cell empty"><em>Grand-mère <?php echo e($role === 'Père' ? 'paternelle' : 'maternelle'); ?></em><strong>À compléter</strong></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee->pere): ?><?php if (isset($component)) { $__componentOriginal235cedc0302cc9d8ee9ee255e3519626 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.health-table','data' => ['chat' => $portee->pere]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('health-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portee->pere)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $attributes = $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $component = $__componentOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee->mere): ?><?php if (isset($component)) { $__componentOriginal235cedc0302cc9d8ee9ee255e3519626 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.health-table','data' => ['chat' => $portee->mere]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('health-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($portee->mere)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $attributes = $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $component = $__componentOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee->events->isNotEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Suivi de la portée']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Suivi de la portée']); ?>
                        <?php if (isset($component)) { $__componentOriginal93f2afea2d7941ca7799292711b7f46f = $component; } ?>
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
<?php endif; ?>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($chaton->estDisponible())): ?>
                    <div class="record">
                        <p class="note" style="border-top:0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chaton->statut === \App\Enums\KittenStatus::Reserve): ?>
                                <?php echo e($chaton->nom); ?> est réservé. Vous pouvez demander à être prévenu en priorité de la prochaine portée.
                            <?php else: ?>
                                <?php echo e($chaton->nom); ?> a rejoint sa famille. Les fiches restent en ligne pour retracer le travail de l'élevage.
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                    <div class="btnrow">
                        <a class="btn ghost" href="<?php echo e(route('kittens.index', ['statut' => 'disponible'])); ?>">Voir les chatons disponibles</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="band tight" style="padding-block:clamp(22px,3vw,36px)">
    <?php if (isset($component)) { $__componentOriginal88b2be7e5b1343afa0d4e3e348532adf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-strip','data' => ['titre' => 'La portée en images']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'La portée en images']); ?>
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

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chaton->estDisponible()): ?>
<div class="kbar">
    <div class="wrap in">
        <span class="nm"><?php echo e($chaton->nom); ?></span>
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
        <span class="small" style="font-size:.82rem"><?php echo e(\Illuminate\Support\Str::ucfirst($chaton->sexe)); ?> · <?php echo e($chaton->robe); ?></span>
        <a class="btn" href="<?php echo e(route('adoption.create', ['chaton' => $chaton->id])); ?>">Pré-réserver <?php echo e($chaton->nom); ?></a>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/kittens/show.blade.php ENDPATH**/ ?>