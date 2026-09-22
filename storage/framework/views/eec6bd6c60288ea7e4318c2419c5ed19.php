

<?php $__env->startSection('title', "Contact — venir voir les chatons"); ?>
<?php $__env->startSection('description', "Écrivez-nous ou appelez l'élevage Chatterie du Temple des Fées à Lapeyrouse-Mornay (38), à 20 minutes de Lyon. Visites sur rendez-vous, réponse sous 48 heures."); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/map.js'); ?>
<?php $__env->stopPush(); ?>

<?php
    $tel    = \App\Models\Setting::get('contact.telephone');
    $telRaw = \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33');
    $mail   = \App\Models\Setting::get('contact.email');
    $insta  = \App\Models\Setting::get('contact.instagram');
?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'Contact','titre' => 'Écrivez-nous, ou appelez','lede' => 'Un appel vaut souvent mieux qu\'un long formulaire — nous décrochons en soirée et le week-end. Si vous préférez écrire, tout est ci-dessous : réponse sous 48 heures.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'Contact','titre' => 'Écrivez-nous, ou appelez','lede' => 'Un appel vaut souvent mieux qu\'un long formulaire — nous décrochons en soirée et le week-end. Si vous préférez écrire, tout est ci-dessous : réponse sous 48 heures.']); ?>
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

        <div class="two off" style="align-items:start">

            
            <div id="formulaire">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('succes')): ?>
                    <p class="flash"><?php echo e(session('succes')); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="flash err">
                        Votre message n'a pas pu être envoyé :
                        <ul>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $erreur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($erreur); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form class="demo form-dark" method="POST" action="<?php echo e(route('contact.store')); ?>">
                    <?php echo csrf_field(); ?>

                    
                    <div style="position:absolute;left:-9999px" aria-hidden="true">
                        <label for="c-site">Site</label>
                        <input type="text" id="c-site" name="site" tabindex="-1" autocomplete="off">
                    </div>

                    <fieldset class="field full" style="border:0;padding:0;margin:0">
                        <legend style="padding:0;margin-bottom:11px"
                                class="mono" >Votre demande</legend>
                        <div class="objets">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $objets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cle => $libelle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label for="c-objet-<?php echo e($cle); ?>">
                                    <input type="radio" id="c-objet-<?php echo e($cle); ?>" name="objet" value="<?php echo e($cle); ?>"
                                           <?php if(old('objet', 'adoption') === $cle): echo 'checked'; endif; ?>>
                                    <span><?php echo e($libelle); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </fieldset>

                    <div class="field">
                        <label for="c-prenom">Prénom</label>
                        <input id="c-prenom" name="prenom" type="text" autocomplete="given-name"
                               value="<?php echo e(old('prenom')); ?>" required>
                    </div>
                    <div class="field">
                        <label for="c-nom">Nom</label>
                        <input id="c-nom" name="nom" type="text" autocomplete="family-name" value="<?php echo e(old('nom')); ?>">
                    </div>
                    <div class="field">
                        <label for="c-email">Email</label>
                        <input id="c-email" name="email" type="email" autocomplete="email"
                               value="<?php echo e(old('email')); ?>" required>
                    </div>
                    <div class="field">
                        <label for="c-tel">Téléphone</label>
                        <input id="c-tel" name="telephone" type="tel" autocomplete="tel" value="<?php echo e(old('telephone')); ?>">
                    </div>

                    <div class="field full">
                        <label for="c-message">Votre message</label>
                        <textarea id="c-message" name="message" required
                                  placeholder="Dites-nous ce qui vous amène : un chaton en particulier, une visite, une question sur la race…"><?php echo e(old('message')); ?></textarea>
                    </div>

                    <label class="consent" for="c-rgpd">
                        <input type="checkbox" id="c-rgpd" name="rgpd" value="1" <?php if(old('rgpd')): echo 'checked'; endif; ?>>
                        <span>
                            J'accepte que Chatterie du Temple des Fées conserve ces informations pour répondre à mon
                            message. Elles ne sont jamais transmises à un tiers et sont supprimées au bout
                            de <?php echo e(\App\Models\ContactMessage::MOIS_CONSERVATION); ?> mois.
                            <a href="<?php echo e(route('legal')); ?>">Politique de confidentialité</a>
                        </span>
                    </label>

                    <div class="full"><button class="btn" type="submit">Envoyer le message</button></div>
                </form>
            </div>

            
            <div class="stack" style="gap:20px">
                <?php if (isset($component)) { $__componentOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a8d2b8d786f4c4fa5a42ce19174e7f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.record','data' => ['titre' => 'Nous joindre directement']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('record'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Nous joindre directement']); ?>
                    <table>
                        <tr><th>Téléphone</th><td><a href="tel:<?php echo e($telRaw); ?>" style="color:var(--bronze-lt);text-decoration:none"><?php echo e($tel); ?></a></td></tr>
                        <tr><th>Email</th><td><a href="mailto:<?php echo e($mail); ?>" style="color:var(--bronze-lt);text-decoration:none"><?php echo e($mail); ?></a></td></tr>
                        <tr><th>Visites</th><td>Sur rendez-vous, week-end et fin de journée</td></tr>
                        <tr><th>Réponse</th><td>Sous 48 heures maximum</td></tr>
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

                <div class="socials">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($insta): ?>
                        <?php if (isset($component)) { $__componentOriginal42dc1172f6584fa18bcf05ef13c89852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-link','data' => ['url' => $insta]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($insta)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $attributes = $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $component = $__componentOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal42dc1172f6584fa18bcf05ef13c89852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-link','data' => ['type' => 'mail','url' => 'mailto:'.$mail,'handle' => $mail]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'mail','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('mailto:'.$mail),'handle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mail)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $attributes = $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $component = $__componentOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal42dc1172f6584fa18bcf05ef13c89852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-link','data' => ['type' => 'tel','url' => 'tel:'.$telRaw,'handle' => $tel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'tel','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('tel:'.$telRaw),'handle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tel)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $attributes = $__attributesOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__attributesOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852)): ?>
<?php $component = $__componentOriginal42dc1172f6584fa18bcf05ef13c89852; ?>
<?php unset($__componentOriginal42dc1172f6584fa18bcf05ef13c89852); ?>
<?php endif; ?>
                </div>

                <div class="btnrow">
                    <a class="btn" href="tel:<?php echo e($telRaw); ?>">Appeler l'élevage</a>
                    <a class="btn ghost" href="<?php echo e(route('adoption.create')); ?>">Demander une visite</a>
                </div>

                <figure class="figure" style="margin:0">
                    <img src="<?php echo e(asset('images/cats/ambiance.webp')); ?>"
                         alt="Maine Coon de la chatterie Chatterie du Temple des Fées" loading="lazy">
                    <figcaption>Fin de journée à la maison</figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>



<section class="band paper tight" id="avis">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Ils sont passés par là','titre' => 'Ce que disent les familles','lede' => 'Témoignages de familles adoptantes, publiés avec leur accord. Prénom seul — aucun nom de famille n\'est publié sur ce site.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Ils sont passés par là','titre' => 'Ce que disent les familles','lede' => 'Témoignages de familles adoptantes, publiés avec leur accord. Prénom seul — aucun nom de famille n\'est publié sur ce site.']); ?>
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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($avis->isNotEmpty()): ?>
            <div class="cells">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $avis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="cell-b">
                        <span class="n"><?php echo e($a->etoiles()); ?></span>
                        <p><?php echo e($a->texte); ?></p>
                        <span class="n" style="margin-top:auto"><?php echo e($a->prenom); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($a->publie_le): ?> · <?php echo e($a->publie_le->translatedFormat('F Y')); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('succes_avis')): ?>
            <p class="flash" style="margin-top:26px"><?php echo e(session('succes_avis')); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->avis->any()): ?>
            <div class="flash err" style="margin-top:26px">
                Votre avis n'a pas pu être envoyé :
                <ul>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->avis->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $erreur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($erreur); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <details class="depot" <?php if(session('succes_avis') || $errors->avis->any()): ?> open <?php endif; ?>>
            <summary>Vous avez adopté chez nous ? Laissez votre avis</summary>

            <form class="demo" method="POST" action="<?php echo e(route('reviews.store')); ?>">
                <?php echo csrf_field(); ?>

                
                <div style="position:absolute;left:-9999px" aria-hidden="true">
                    <label for="a-site">Site</label>
                    <input type="text" id="a-site" name="site" tabindex="-1" autocomplete="off">
                </div>

                <div class="field">
                    <label for="a-prenom">Prénom</label>
                    <input id="a-prenom" name="prenom" type="text" autocomplete="given-name"
                           value="<?php echo e(old('prenom')); ?>" maxlength="80" required>
                </div>

                <div class="field">
                    <label for="a-note">Note</label>
                    <select id="a-note" name="note" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [5 => '★★★★★', 4 => '★★★★☆', 3 => '★★★☆☆', 2 => '★★☆☆☆', 1 => '★☆☆☆☆']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $libelle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php if((int) old('note', 5) === $v): echo 'selected'; endif; ?>><?php echo e($libelle); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div class="field full" style="grid-column:1/-1">
                    <label for="a-email">Email <span style="text-transform:none;letter-spacing:0">— facultatif, jamais publié</span></label>
                    <input id="a-email" name="email" type="email" autocomplete="email"
                           value="<?php echo e(old('email')); ?>" maxlength="150"
                           placeholder="Pour vous recontacter si nous avons une question">
                </div>

                <div class="field full" style="grid-column:1/-1">
                    <label for="a-texte">Votre avis</label>
                    <textarea id="a-texte" name="texte" maxlength="1500" required
                              placeholder="Votre expérience avec l'élevage : la préparation, la visite, l'arrivée du chaton chez vous."><?php echo e(old('texte')); ?></textarea>
                </div>

                <label class="consent" for="a-rgpd">
                    <input type="checkbox" id="a-rgpd" name="rgpd" value="1" <?php if(old('rgpd')): echo 'checked'; endif; ?>>
                    <span>
                        J'accepte que mon avis soit publié sur ce site sous mon prénom seul, et que
                        Chatterie du Temple des Fées conserve mon adresse email si je l'ai renseignée, uniquement pour
                        me recontacter. Mon avis est relu avant publication.
                        <a href="<?php echo e(route('legal')); ?>">Politique de confidentialité</a>
                    </span>
                </label>

                <div class="btnrow" style="grid-column:1/-1">
                    <button class="btn" type="submit">Envoyer mon avis</button>
                </div>
            </form>
        </details>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($avisGoogle = \App\Models\Setting::get('contact.avis_google')): ?>
            <p style="margin-top:24px">
                <a class="tlink" href="<?php echo e($avisGoogle); ?>" target="_blank" rel="noopener noreferrer">
                    Voir tous les avis sur Google
                </a>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>


<section class="band ink2 tight">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Venir jusqu\'à nous','titre' => 'À vingt minutes de Lyon','lede' => 'L\'élevage est à Lapeyrouse-Mornay, en Isère. L\'adresse exacte vous est communiquée lors de la prise de rendez-vous — la carte situe la zone et les principaux accès.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Venir jusqu\'à nous','titre' => 'À vingt minutes de Lyon','lede' => 'L\'élevage est à Lapeyrouse-Mornay, en Isère. L\'adresse exacte vous est communiquée lors de la prise de rendez-vous — la carte situe la zone et les principaux accès.']); ?>
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

        <div class="mapwrap">
            <div id="carte" data-carte='<?php echo json_encode($points, 15, 512) ?>' role="application"
                 aria-label="Carte de situation de l'élevage à Lapeyrouse-Mornay"></div>
            <div class="mapcard">
                <h4>Temps de trajet</h4>
                <dl>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $points['reperes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repere): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <dt><?php echo e($repere['titre']); ?></dt>
                        <dd><?php echo e($repere['detail']); ?></dd>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </dl>
                <p class="small" style="font-size:.78rem">
                    Nous pouvons venir vous chercher à la gare de La Verpillière.
                </p>
                
                <h4 style="margin-top:24px">Itinéraire</h4>
                <div class="btnrow" style="margin-top:10px">
                    <a class="btn ghost" href="<?php echo e($itineraire['google']); ?>"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Itinéraire vers <?php echo e($itineraire['commune']); ?> sur Google Maps (nouvelle fenêtre)"
                       style="flex:1;justify-content:center;padding:11px 12px;font-size:.8rem">Google Maps</a>
                    <a class="btn ghost" href="<?php echo e($itineraire['waze']); ?>"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Itinéraire vers <?php echo e($itineraire['commune']); ?> sur Waze (nouvelle fenêtre)"
                       style="flex:1;justify-content:center;padding:11px 12px;font-size:.8rem">Waze</a>
                </div>
            </div>
        </div>

        <p class="small" style="margin-top:16px">
            Carte &copy; OpenStreetMap et CARTO. Aucun traceur publicitaire n'est chargé sur cette page :
            les liens d'itinéraire ouvrent Google Maps ou Waze dans un nouvel onglet, rien n'est chargé depuis eux ici.
        </p>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/contact.blade.php ENDPATH**/ ?>