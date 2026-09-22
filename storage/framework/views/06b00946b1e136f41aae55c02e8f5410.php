

<?php $__env->startSection('title', $chat->nom.' — '.$chat->role->libelle()); ?>
<?php $__env->startSection('description', \Illuminate\Support\Str::limit(strip_tags($chat->description), 150)); ?>
<?php $__env->startSection('og_image', asset($chat->photo_principale)); ?>

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
                    <a href="<?php echo e(route('cats.index')); ?>" style="text-decoration:none">← L'élevage</a> · <?php echo e($chat->role->libelle()); ?>

                </span>
                <h1><?php echo e($chat->nom); ?></h1>
                <p class="lede"><?php echo e($chat->description); ?></p>
            </div>
        </div>

        <div class="detail">
            <div class="stack" style="gap:14px">
                <?php if (isset($component)) { $__componentOriginaleaab132dbe310803d7778e2c66aa2d41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleaab132dbe310803d7778e2c66aa2d41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-viewer','data' => ['photos' => $chat->galerie()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-viewer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['photos' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chat->galerie())]); ?>
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
            </div>

            <div class="stack" style="gap:22px">
                <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Identité','meta' => ''.e($chat->role->libelle()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Identité','meta' => ''.e($chat->role->libelle()).'']); ?>
                    <table>
                        <tr><th>Sexe</th><td><?php echo e(\Illuminate\Support\Str::ucfirst($chat->sexe)); ?></td></tr>
                        <tr><th>Année de naissance</th><td><?php echo e($chat->annee_naissance); ?></td></tr>
                        <tr><th>Robe</th><td><?php echo e($chat->robe); ?></td></tr>
                        <tr><th>Pedigree LOOF</th><td class="<?php echo \Illuminate\Support\Arr::toCssClasses(['todo' => blank($chat->loof_numero)]); ?>"><?php echo e($chat->loof_numero ?? 'À compléter'); ?></td></tr>
                        <tr><th>Identification ICAD</th><td class="<?php echo \Illuminate\Support\Arr::toCssClasses(['todo' => blank($chat->icad_numero)]); ?>"><?php echo e($chat->icad_numero ?? 'À compléter'); ?></td></tr>
                        <tr><th>Bilan santé</th>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chat->bilanSanteComplet()): ?>
                                    <b style="color:var(--ok)">Complet</b>
                                <?php else: ?>
                                    <b style="color:var(--bronze-lt)">En cours</b><br>
                                    <span class="small" style="font-size:.79rem">Pas de mise à la reproduction tant qu'il n'est pas complet.</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
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

                <?php if (isset($component)) { $__componentOriginal235cedc0302cc9d8ee9ee255e3519626 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.health-table','data' => ['chat' => $chat]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('health-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chat)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $attributes = $__attributesOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__attributesOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626)): ?>
<?php $component = $__componentOriginal235cedc0302cc9d8ee9ee255e3519626; ?>
<?php unset($__componentOriginal235cedc0302cc9d8ee9ee255e3519626); ?>
<?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portees->isNotEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Portées']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Portées']); ?>
                        <table>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $portees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th><?php echo e($portee->code); ?> — <?php echo e($portee->date_naissance->translatedFormat('F Y')); ?></th>
                                    <td><?php echo e($portee->kittens_count); ?> chatons</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="btnrow">
                    <a class="btn ghost" href="<?php echo e(route('kittens.index')); ?>">Voir la portée en cours</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($autres->isNotEmpty()): ?>
<section class="band ink2">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'La lignée','titre' => 'Les autres chats de l\'élevage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'La lignée','titre' => 'Les autres chats de l\'élevage']); ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $autres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal2b14af9ad13f574c518b459e5d740aba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b14af9ad13f574c518b459e5d740aba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cat-card','data' => ['chat' => $autre]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($autre)]); ?>
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
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/cats/show.blade.php ENDPATH**/ ?>