

<?php $__env->startSection('title', "Mentions légales et protection des données"); ?>
<?php $__env->startSection('description', "Mentions légales de Chatterie du Temple des Fées, conditions de cession des chatons et traitement des données personnelles."); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap" style="max-width:840px">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Informations légales','titre' => 'Mentions légales &amp; confidentialité','lede' => 'Les informations que tout site d\'élevage doit publier. Les champs marqués « à compléter » attendent les numéros officiels.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Informations légales','titre' => 'Mentions légales &amp; confidentialité','lede' => 'Les informations que tout site d\'élevage doit publier. Les champs marqués « à compléter » attendent les numéros officiels.']); ?>
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

        <div class="stack" style="gap:26px">

            <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Éditeur du site']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Éditeur du site']); ?>
                <table>
                    <tr><th>Dénomination</th><td><?php echo e(\App\Models\Setting::get('elevage.nom')); ?></td></tr>
                    <tr><th>Adresse</th><td><?php echo e(\App\Models\Setting::get('elevage.ville')); ?> (<?php echo e(\App\Models\Setting::get('elevage.code_postal')); ?>), <?php echo e(\App\Models\Setting::get('elevage.departement')); ?></td></tr>
                    <tr><th>SIREN / SIRET</th><td><?php if (isset($component)) { $__componentOriginal6bdffa2cf791404978f6ba447c027231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bdffa2cf791404978f6ba447c027231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.legal-value','data' => ['cle' => 'legal.siren']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('legal-value'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cle' => 'legal.siren']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $attributes = $__attributesOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__attributesOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $component = $__componentOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__componentOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?></td></tr>
                    <tr><th>Certificat de capacité</th><td><?php if (isset($component)) { $__componentOriginal6bdffa2cf791404978f6ba447c027231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bdffa2cf791404978f6ba447c027231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.legal-value','data' => ['cle' => 'legal.certificat']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('legal-value'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cle' => 'legal.certificat']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $attributes = $__attributesOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__attributesOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $component = $__componentOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__componentOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?></td></tr>
                    <tr><th>Enregistrement</th><td>Chambre d'agriculture</td></tr>
                    <tr><th>Directeur de la publication</th><td><?php if (isset($component)) { $__componentOriginal6bdffa2cf791404978f6ba447c027231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bdffa2cf791404978f6ba447c027231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.legal-value','data' => ['cle' => 'legal.directeur']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('legal-value'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cle' => 'legal.directeur']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $attributes = $__attributesOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__attributesOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $component = $__componentOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__componentOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?></td></tr>
                    <tr><th>Hébergeur</th><td><?php if (isset($component)) { $__componentOriginal6bdffa2cf791404978f6ba447c027231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6bdffa2cf791404978f6ba447c027231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.legal-value','data' => ['cle' => 'legal.hebergeur']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('legal-value'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['cle' => 'legal.hebergeur']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $attributes = $__attributesOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__attributesOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6bdffa2cf791404978f6ba447c027231)): ?>
<?php $component = $__componentOriginal6bdffa2cf791404978f6ba447c027231; ?>
<?php unset($__componentOriginal6bdffa2cf791404978f6ba447c027231); ?>
<?php endif; ?></td></tr>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Cession de chatons','note' => 'Chaque annonce de cession affiche le numéro d\'identification du chaton, le numéro de portée LOOF, l\'âge et le nombre d\'animaux de la portée, conformément à la réglementation applicable aux cessions d\'animaux de compagnie.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Cession de chatons','note' => 'Chaque annonce de cession affiche le numéro d\'identification du chaton, le numéro de portée LOOF, l\'âge et le nombre d\'animaux de la portée, conformément à la réglementation applicable aux cessions d\'animaux de compagnie.']); ?>
                <table>
                    <tr><th>Âge minimum</th><td><?php echo e(\App\Models\Litter::SEMAINES_AVANT_CESSION); ?> semaines révolues</td></tr>
                    <tr><th>Identification</th><td>Puce électronique enregistrée à l'ICAD avant toute cession</td></tr>
                    <tr><th>Inscription</th><td>LOOF — pedigree remis à la famille, jamais en option</td></tr>
                    <tr><th>Certificat d'engagement</th><td>Remis et signé au minimum 7 jours avant la cession. Ce délai de réflexion est incompressible : aucun chaton ne part avant son terme.</td></tr>
                    <tr><th>Documents remis</th><td>Certificat vétérinaire de bonne santé, carnet de vaccination, contrat de cession, document d'information sur les besoins de l'espèce</td></tr>
                    <tr><th>Reprise</th><td>Prévue au contrat, sans limite d'âge</td></tr>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Données personnelles','note' => 'Les statuts « réservé » et « adopté » sont affichés sans aucune donnée nominative sur la famille concernée. Les témoignages ne sont publiés qu\'avec accord écrit, sous le prénom seul.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Données personnelles','note' => 'Les statuts « réservé » et « adopté » sont affichés sans aucune donnée nominative sur la famille concernée. Les témoignages ne sont publiés qu\'avec accord écrit, sous le prénom seul.']); ?>
                <table>
                    <tr><th>Données collectées</th><td>Identité, coordonnées et informations sur le foyer, uniquement via le formulaire de pré-réservation</td></tr>
                    <tr><th>Finalité</th><td>Traiter la demande d'adoption et assurer le suivi du chaton</td></tr>
                    <tr><th>Base légale</th><td>Consentement, recueilli explicitement au dépôt de la demande</td></tr>
                    <tr><th>Durée de conservation</th><td><?php echo e(\App\Models\AdoptionRequest::MOIS_CONSERVATION); ?> mois après le dépôt de la demande, puis suppression automatique</td></tr>
                    <tr><th>Destinataires</th><td>Chatterie du Temple des Fées uniquement — aucune transmission à un tiers, aucune revente</td></tr>
                    <tr><th>Vos droits</th><td>Accès, rectification, effacement et opposition sur simple demande à <?php echo e(\App\Models\Setting::get('contact.email')); ?></td></tr>
                    <tr><th>Publication des noms</th><td>Aucun nom ni prénom d'adoptant n'est publié sur ce site</td></tr>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Cookies']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Cookies']); ?>
                <table>
                    <tr><th>Cookies déposés</th><td>Aucun cookie de mesure d'audience ni de publicité</td></tr>
                    <tr><th>Cookies techniques</th><td>Uniquement ceux nécessaires au fonctionnement du formulaire (session et jeton CSRF)</td></tr>
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

        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/legal.blade.php ENDPATH**/ ?>