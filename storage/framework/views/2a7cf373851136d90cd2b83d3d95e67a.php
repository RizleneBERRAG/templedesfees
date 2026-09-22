

<?php $__env->startSection('title', "Adopter un chaton Maine Coon — parcours et pré-réservation"); ?>
<?php $__env->startSection('description', "Le parcours d'adoption chez Chatterie du Temple des Fées en quatre étapes, le détail de ce que couvre l'adoption, et le formulaire de pré-réservation."); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Adopter','titre' => 'Le parcours d\'adoption','lede' => 'Quatre étapes, aucune surprise. La demande ne vous engage à rien : elle ouvre la discussion.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Adopter','titre' => 'Le parcours d\'adoption','lede' => 'Quatre étapes, aucune surprise. La demande ne vous engage à rien : elle ouvre la discussion.']); ?>
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

        <div class="cells">
            <div class="cell-b"><span class="n">ÉTAPE 01</span><h3>Vous nous écrivez</h3><p>Le formulaire plus bas, ou un appel. Parlez-nous de votre foyer, de vos autres animaux, de votre rythme de vie. Réponse sous 48 heures.</p></div>
            <div class="cell-b"><span class="n">ÉTAPE 02</span><h3>Vous venez les voir</h3><p>Visite sur rendez-vous à Lapeyrouse-Mornay. Vous rencontrez la mère, la fratrie complète, et vous voyez l'endroit où ils grandissent.</p></div>
            <div class="cell-b"><span class="n">ÉTAPE 03</span><h3>Réservation et contrat</h3><p>Contrat de cession signé, acompte, puis des nouvelles régulières en photo et en vidéo jusqu'au départ.</p></div>
            <div class="cell-b"><span class="n">ÉTAPE 04</span><h3>Le grand jour</h3><p>À <?php echo e(\App\Models\Litter::SEMAINES_AVANT_CESSION); ?> semaines minimum : pedigree LOOF, carnet de santé, certificat vétérinaire, puce ICAD, contrat et kit d'alimentation.</p></div>
        </div>
    </div>
</section>

<section class="band paper" id="couverture">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'La question du tarif','titre' => 'Ce que couvre l\'adoption','lede' => 'Il faut le dire clairement, parce que c\'est la question qui fâche : ce que vous réglez ne paie pas le chat. Voici, ligne par ligne, ce qu\'il y a derrière un chaton qui arrive chez vous.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'La question du tarif','titre' => 'Ce que couvre l\'adoption','lede' => 'Il faut le dire clairement, parce que c\'est la question qui fâche : ce que vous réglez ne paie pas le chat. Voici, ligne par ligne, ce qu\'il y a derrière un chaton qui arrive chez vous.']); ?>
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

        <div class="ledger">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('chatterie.couverture'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row">
                    <span class="mk"><?php echo e($ligne['numero']); ?></span>
                    <span class="ttl"><?php echo e($ligne['titre']); ?><small><?php echo e($ligne['detail']); ?></small></span>
                    <span class="who"><?php echo e($ligne['quand']); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="foot">
                <p class="quote">« Derrière chaque chaton, il y a plusieurs mois de présence et de travail. »</p>
                <p class="lede">
                    C'est cette prise en charge globale qui représente un coût — bien davantage que le
                    chat lui-même. Si le budget est ce qui vous retient, parlez-nous-en : on trouve
                    souvent une solution, notamment en échelonnant.
                </p>
            </div>
        </div>
    </div>
</section>

<?php if (isset($component)) { $__componentOriginal474f5b35d7f6ada5a194626b31e0203c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal474f5b35d7f6ada5a194626b31e0203c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-band','data' => ['image' => 'images/cats/chatons-pile.webp','legende' => 'Douze semaines ensemble avant le grand départ','hauteur' => '42vh']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => 'images/cats/chatons-pile.webp','legende' => 'Douze semaines ensemble avant le grand départ','hauteur' => '42vh']); ?>
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

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Demande de pré-réservation','titre' => 'Parlez-nous de votre foyer','lede' => 'Plus vous nous en dites, plus nous pourrons vous orienter vers le chaton qui vous correspond vraiment. Réponse sous 48 heures.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Demande de pré-réservation','titre' => 'Parlez-nous de votre foyer','lede' => 'Plus vous nous en dites, plus nous pourrons vous orienter vers le chaton qui vous correspond vraiment. Réponse sous 48 heures.']); ?>
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

        <div style="max-width:880px">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('succes')): ?>
                <div class="record" style="margin-bottom:26px">
                    <p class="note" style="border-top:0;color:var(--ok)"><?php echo e(session('succes')); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="record" style="margin-bottom:26px">
                    <div class="hd"><span class="mono">Demande incomplète</span></div>
                    <p class="note" style="border-top:0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $erreur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($erreur); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form class="demo" method="POST" action="<?php echo e(route('adoption.store')); ?>">
                <?php echo csrf_field(); ?>

                
                <div style="position:absolute;left:-9999px" aria-hidden="true">
                    <label for="f-site">Site</label>
                    <input type="text" id="f-site" name="site" tabindex="-1" autocomplete="off">
                </div>

                <div class="field"><label for="f-prenom">Prénom</label>
                    <input id="f-prenom" name="prenom" type="text" autocomplete="given-name" value="<?php echo e(old('prenom')); ?>" required></div>
                <div class="field"><label for="f-nom">Nom</label>
                    <input id="f-nom" name="nom" type="text" autocomplete="family-name" value="<?php echo e(old('nom')); ?>"></div>
                <div class="field"><label for="f-email">Email</label>
                    <input id="f-email" name="email" type="email" autocomplete="email" value="<?php echo e(old('email')); ?>" required></div>
                <div class="field"><label for="f-tel">Téléphone</label>
                    <input id="f-tel" name="telephone" type="tel" autocomplete="tel" value="<?php echo e(old('telephone')); ?>"></div>
                <div class="field"><label for="f-cp">Code postal</label>
                    <input id="f-cp" name="code_postal" type="text" inputmode="numeric" autocomplete="postal-code" value="<?php echo e(old('code_postal')); ?>"></div>

                <div class="field"><label for="f-chaton">Chaton souhaité</label>
                    <select id="f-chaton" name="kitten_id">
                        <option value="">Sans préférence</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $disponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chaton): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($chaton->id); ?>"
                                <?php if(old('kitten_id', request('chaton')) == $chaton->id): echo 'selected'; endif; ?>>
                                <?php echo e($chaton->nom); ?> — <?php echo e(\Illuminate\Support\Str::ucfirst($chaton->sexe)); ?> — <?php echo e($chaton->robe); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div class="field"><label for="f-logement">Votre logement</label>
                    <select id="f-logement" name="logement">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Appartement', 'Maison avec jardin', 'Maison sans jardin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if(old('logement') === $choix): echo 'selected'; endif; ?>><?php echo e($choix); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="field"><label for="f-animaux">Autres animaux</label>
                    <select id="f-animaux" name="autres_animaux">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Aucun', 'Un chat', 'Plusieurs chats', 'Un chien', 'Chien(s) et chat(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if(old('autres_animaux') === $choix): echo 'selected'; endif; ?>><?php echo e($choix); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="field"><label for="f-presence">Présence à la maison</label>
                    <select id="f-presence" name="presence">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Quelqu\'un est là la journée', 'Absent la journée en semaine', 'Télétravail partiel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if(old('presence') === $choix): echo 'selected'; endif; ?>><?php echo e($choix); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="field"><label for="f-exp">Expérience avec les chats</label>
                    <select id="f-exp" name="experience">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Premier chat', 'J\'ai déjà eu des chats', 'J\'ai déjà eu un Maine Coon']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if(old('experience') === $choix): echo 'selected'; endif; ?>><?php echo e($choix); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div class="field full"><label for="f-msg">Votre message</label>
                    <textarea id="f-msg" name="message" placeholder="Parlez-nous de votre rythme de vie, de ce que vous attendez d'un Maine Coon, de vos questions…"><?php echo e(old('message')); ?></textarea></div>

                <label class="consent" for="f-rgpd">
                    <input type="checkbox" id="f-rgpd" name="rgpd" value="1" <?php if(old('rgpd')): echo 'checked'; endif; ?>>
                    <span>
                        J'accepte que Chatterie du Temple des Fées conserve ces informations pour traiter ma demande
                        d'adoption. Elles ne sont jamais transmises à un tiers et sont supprimées au bout
                        de <?php echo e(\App\Models\AdoptionRequest::MOIS_CONSERVATION); ?> mois.
                        <a href="<?php echo e(route('legal')); ?>" style="color:var(--bronze-dim)">Politique de confidentialité</a>
                    </span>
                </label>

                <div class="full"><button class="btn" type="submit">Envoyer ma demande</button></div>
            </form>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/adoption.blade.php ENDPATH**/ ?>