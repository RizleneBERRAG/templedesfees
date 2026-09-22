<?php
    $tel   = \App\Models\Setting::get('contact.telephone', '06 77 35 45 87');
    $mail  = \App\Models\Setting::get('contact.email', 'letempledesfees@outlook.fr');
    $insta = \App\Models\Setting::get('contact.instagram');
    $siren = \App\Models\Setting::get('legal.siren');
    $telLien = \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33');
?>

<footer>
    <div class="wrap">
        <div class="fgrille">
            <div>
                <h4>Chatterie du Temple des Fées</h4>
                <p class="petit" style="max-width:36ch">
                    Élevage familial de Maine Coon à Lapeyrouse-Mornay (26210), dans la Drôme
                    des collines. Une à deux portées par an, parents dépistés, résultats publiés.
                </p>
                <?php if (isset($component)) { $__componentOriginal211bc3116c79c8642163dc1f5fe4e306 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fleuron','data' => ['taille' => 'petit','style' => 'margin-top:20px;color:var(--or-mat)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('fleuron'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['taille' => 'petit','style' => 'margin-top:20px;color:var(--or-mat)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $attributes = $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $component = $__componentOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?>
            </div>

            <div>
                <h4>L'élevage</h4>
                <ul>
                    <li><a href="<?php echo e(route('kittens.index')); ?>">Chatons disponibles</a></li>
                    <li><a href="<?php echo e(route('cats.index')); ?>">Nos reproducteurs</a></li>
                    <li><a href="<?php echo e(route('gallery')); ?>">Galerie</a></li>
                    <li><a href="<?php echo e(route('breed')); ?>">Le Maine Coon</a></li>
                </ul>
            </div>

            <div>
                <h4>Adopter</h4>
                <ul>
                    <li><a href="<?php echo e(route('adoption.create')); ?>">Le parcours</a></li>
                    <li><a href="<?php echo e(route('adoption.create')); ?>#couverture">Ce que couvre l'adoption</a></li>
                    <li><a href="<?php echo e(route('faq')); ?>">Questions fréquentes</a></li>
                    <li><a href="<?php echo e(route('legal')); ?>">Mentions légales &amp; RGPD</a></li>
                </ul>
            </div>

            <div>
                <h4>Nous joindre</h4>
                <ul>
                    <li><a href="tel:<?php echo e($telLien); ?>"><?php echo e($tel); ?></a></li>
                    <li><a href="mailto:<?php echo e($mail); ?>"><?php echo e($mail); ?></a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Venir nous voir</a></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($insta): ?>
                        <li><a href="<?php echo e($insta); ?>" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="fbas">
            <span>© <?php echo e(date('Y')); ?> Temple des Fées — Certificat de capacité · SIREN <?php echo e($siren ?: 'à compléter'); ?></span>
            <span>24 chemin Saint-Charles · 26210 Lapeyrouse-Mornay</span>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/partials/footer.blade.php ENDPATH**/ ?>