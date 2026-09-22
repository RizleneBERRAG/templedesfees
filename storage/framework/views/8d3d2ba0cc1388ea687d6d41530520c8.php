

<?php $__env->startSection('title', "Chatterie du Temple des Fées"); ?>
<?php $__env->startSection('description', "Chatterie familiale de Maine Coon à Lapeyrouse-Mornay (26). Une à deux portées par an, parents dépistés HCM, SMA et PK-Def, résultats publiés sur chaque fiche."); ?>

<?php $__env->startPush('schema'); ?>


<?php
    $schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'LocalBusiness',
    '@id'      => route('home').'#elevage',
    'name'     => \App\Models\Setting::get('elevage.nom', 'Chatterie du Temple des Fées'),
    'description' => "Élevage familial de Maine Coon à Lapeyrouse-Mornay, dans la Drôme des collines.",
    'url'      => route('home'),
    'image'    => asset('images/cats/hero-duo.webp'),
    'telephone' => \App\Models\Setting::get('contact.telephone'),
    'email'     => \App\Models\Setting::get('contact.email'),
    'address'  => [
        '@type' => 'PostalAddress',
        'streetAddress'   => '24 chemin Saint-Charles',
        'addressLocality' => \App\Models\Setting::get('elevage.ville'),
        'postalCode'      => \App\Models\Setting::get('elevage.code_postal'),
        'addressRegion'   => \App\Models\Setting::get('elevage.departement'),
        'addressCountry'  => 'FR',
    ],
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => config('chatterie.carte.zone.lat'),
        'longitude' => config('chatterie.carte.zone.lng'),
    ],
    'areaServed' => [
        ['@type' => 'AdministrativeArea', 'name' => 'Drôme'],
        ['@type' => 'AdministrativeArea', 'name' => 'Auvergne-Rhône-Alpes'],
    ],
    'sameAs' => array_values(array_filter([
        \App\Models\Setting::get('contact.instagram'),
    ])),
    'availableLanguage' => 'fr',
];
?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="hero">
    <div class="halo" aria-hidden="true"></div>

    <div class="frise"><?php if (isset($component)) { $__componentOriginal211bc3116c79c8642163dc1f5fe4e306 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fleuron','data' => ['taille' => 'moyen']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('fleuron'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['taille' => 'moyen']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $attributes = $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $component = $__componentOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?></div>

    <div class="duo">
        <div class="portail arche">
            <i><u>
                <img src="<?php echo e(asset('images/cats/hero-duo.webp')); ?>"
                     alt="Maine Coon de la chatterie du Temple des Fées, installé à la maison"
                     width="1200" height="1599" fetchpriority="high">
            </u></i>
        </div>

        <div class="texte">
        <span class="rubrique">Chatterie du Temple des Fées · Lapeyrouse-Mornay (26)</span>
        <h1 class="or">
            <span class="leve"><span>Le sanctuaire</span></span>
            <span class="leve"><span><em>des géants doux</em></span></span>
        </h1>
        <p class="lede">
            Un élevage familial de Maine Coon dans la Drôme des collines. Une à deux portées
            par an, nées au milieu de la maison. Chaque parent dépisté, chaque résultat publié.
        </p>
        <div class="btnrow">
            <a class="btn" href="<?php echo e(route('kittens.index')); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nbDispo > 0): ?> Les <?php echo e($nbDispo); ?> chatons disponibles <?php else: ?> Voir la portée en cours <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
            <a class="btn creux" href="<?php echo e(route('adoption.create')); ?>">Le parcours d'adoption</a>
        </div>
        </div>
    </div>

    <p class="signature">À la maison · Hiver 2026</p>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee): ?>
<div class="vivant">
    <div class="wrap in">
        <span class="pouls" aria-hidden="true"></span>
        <span class="rubrique" style="letter-spacing:.24em"><?php echo e($portee->code); ?> · <?php echo e($portee->pere?->nom); ?> × <?php echo e($portee->mere?->nom); ?></span>
        <span>
            <strong><?php echo e($nbDispo); ?> chaton<?php echo e($nbDispo > 1 ? 's' : ''); ?> disponible<?php echo e($nbDispo > 1 ? 's' : ''); ?></strong>
            <span style="color:var(--ivoire-dim)">— né<?php echo e($portee->nb_chatons > 1 ? 's' : ''); ?> le <?php echo e($portee->date_naissance->translatedFormat('j F Y')); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portee->phraseDisponibilite()): ?>, <?php echo e($portee->phraseDisponibilite()); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
        </span>
        <a class="lien" href="<?php echo e(route('kittens.index')); ?>">Voir la portée</a>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chats->isNotEmpty()): ?>
<section class="bande">
    <div class="wrap">
        <div class="frise" style="margin-bottom:clamp(34px,5vw,54px)"><?php if (isset($component)) { $__componentOriginal211bc3116c79c8642163dc1f5fe4e306 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fleuron','data' => ['taille' => 'grand']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('fleuron'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['taille' => 'grand']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $attributes = $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__attributesOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306)): ?>
<?php $component = $__componentOriginal211bc3116c79c8642163dc1f5fe4e306; ?>
<?php unset($__componentOriginal211bc3116c79c8642163dc1f5fe4e306); ?>
<?php endif; ?></div>

        <div class="chapitre monte">
            <span class="numero">Chapitre premier</span>
            <h2>Ceux qui vivent ici</h2>
            <p class="lede">
                Onze Maine Coon, tous à la maison — pas en cage, pas en box. Les reproductrices
                mettent bas dans le salon, et les retraitées restent jusqu'au bout.
            </p>
        </div>

        <div class="retable">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chats->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="portrait monte" href="<?php echo e(route('cats.show', $chat)); ?>">
                    <div class="arche petite">
                        <i><u>
                            <img src="<?php echo e(asset($chat->photo_principale)); ?>"
                                 alt="<?php echo e($chat->nom); ?>, Maine Coon <?php echo e(\Illuminate\Support\Str::lower($chat->robe)); ?>"
                                 loading="lazy" width="900" height="1255">
                        </u></i>
                    </div>
                    <b><?php echo e($chat->nom); ?></b>
                    <small><?php echo e($chat->robe); ?> · <?php echo e($chat->role->libelle()); ?></small>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chats->count() > 3): ?>
            <div style="display:flex;justify-content:center;margin-top:clamp(32px,4vw,48px)">
                <a class="lien" href="<?php echo e(route('cats.index')); ?>">Les <?php echo e($chats->count()); ?> chats de l'élevage</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vitrine): ?>
<section class="bande creuse">
    <div class="wrap">
        <div class="registre monte">
            <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>
            <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>

            <div style="display:flex;gap:clamp(24px,4vw,56px);align-items:flex-start;flex-wrap:wrap">
                <div style="width:min(380px,100%);display:flex;flex-direction:column;gap:16px">
                    <span class="numero" style="font-family:var(--pierre);font-size:12px;letter-spacing:.34em;text-transform:uppercase;color:var(--or-mat)">Chapitre second</span>
                    <h2 style="font-size:clamp(1.9rem,3.6vw,3rem)">Le registre<br>de santé</h2>
                    <p style="font-size:15px;line-height:1.78;color:#B3AB99">
                        La cardiomyopathie hypertrophique est la maladie de la race. Elle se dépiste
                        de deux façons qui ne se remplacent pas : le test ADN cherche les mutations
                        connues, l'échographie regarde le cœur tel qu'il est aujourd'hui. Les deux
                        figurent ici, avec leur date.
                    </p>
                    <a class="lien" href="<?php echo e(route('cats.show', $vitrine)); ?>">La fiche complète de <?php echo e($vitrine->nom); ?></a>
                </div>

                <div style="flex:1 1 420px;min-width:0">
                    <div class="entete">
                        <b><?php echo e($vitrine->nom); ?></b>
                        <span><?php echo e($vitrine->loof_numero ? 'Pedigree LOOF '.$vitrine->loof_numero : 'Pedigree LOOF à compléter'); ?></span>
                    </div>
                    <table>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vitrine->healthTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th><?php echo e($test->type->libelle()); ?></th>
                                <td>
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['verdict', 'attente' => $test->estEnAttente()]); ?>">
                                        <?php echo e($test->resultat ?: 'À programmer'); ?>

                                    </span>
                                    <small>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($test->date_examen): ?>
                                            <?php echo e($test->laboratoire ? $test->laboratoire.' · ' : ''); ?><?php echo e($test->date_examen->translatedFormat('j F Y')); ?>

                                        <?php else: ?>
                                            <?php echo e($test->type->methode()); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </small>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </table>
                    <p class="note">
                        Un test ADN se fait une fois pour la vie : le génome ne change pas. Une
                        échocardiographie ne vaut que pour le jour où elle a été faite, et se
                        renouvelle tant que le chat reproduit. Une ligne encore vide s'affiche
                        telle quelle, en or — nous ne masquons pas ce qui manque.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chatons->isNotEmpty()): ?>
<section class="bande">
    <div class="wrap">
        <div class="chapitre monte">
            <span class="numero">Chapitre troisième</span>
            <h2>La portée en cours</h2>
            <p class="lede">
                Ils sont nés dans le salon, au bruit de la maison. Chaque fiche porte le numéro
                d'identification du chaton et le numéro de portée LOOF — sans eux, elle reste
                en brouillon et n'est jamais publiée.
            </p>
        </div>

        <div class="fiches">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chatons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chaton): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="fiche monte <?php if($chaton->statut === \App\Enums\KittenStatus::Adopte): ?> partie <?php endif; ?>"
                   href="<?php echo e(route('kittens.show', $chaton)); ?>">
                    <div class="arche petite">
                        <span class="pastille <?php echo e($chaton->statut->value); ?>"><?php echo e($chaton->statut->libelle()); ?></span>
                        <i><u>
                            <img src="<?php echo e(asset($chaton->photo_principale)); ?>"
                                 alt="<?php echo e($chaton->nom); ?>, chaton Maine Coon <?php echo e(\Illuminate\Support\Str::lower($chaton->robe)); ?>"
                                 loading="lazy" width="900" height="1125">
                        </u></i>
                    </div>
                    <span class="bd">
                        <b><?php echo e($chaton->nom); ?></b>
                        <small><?php echo e($chaton->robe); ?> · <?php echo e(\Illuminate\Support\Str::lower($chaton->sexe)); ?></small>
                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<section class="bande creuse">
    <div class="wrap">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:clamp(30px,5vw,68px);align-items:start">
            <div class="chapitre gauche monte" style="margin-bottom:0">
                <span class="numero">Chapitre quatrième</span>
                <h2>Le départ</h2>
                <p class="lede">
                    Un chaton ne se commande pas. Il part à douze semaines au plus tôt, identifié,
                    vacciné, vermifugé, avec son pedigree et son contrat. Entre la première visite
                    et le jour du départ, il se passe trois mois.
                </p>
                <div class="btnrow" style="margin-top:8px">
                    <a class="btn creux" href="<?php echo e(route('adoption.create')); ?>">Le parcours en détail</a>
                </div>
            </div>

            <ol class="chrono monte">
                <li class="faite">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Premier contact</span>
                    <span class="quoi">Un message, puis un appel. Nous parlons de votre foyer, de vos horaires, de vos autres animaux. Il n'y a pas de mauvaise réponse, mais il y a des mauvais moments.</span></span>
                </li>
                <li class="faite">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">La visite</span>
                    <span class="quoi">Vous venez à la maison, vous voyez les parents, vous voyez où les chatons grandissent. L'adresse exacte est communiquée au rendez-vous.</span></span>
                </li>
                <li class="encours">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">La réservation</span>
                    <span class="quoi">Un contrat écrit, un acompte, et le chaton vous est réservé. Vous recevez des photos toutes les semaines jusqu'au départ.</span></span>
                </li>
                <li>
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Douze semaines</span>
                    <span class="quoi">Identifié, primo-vacciné et rappelé, vermifugé, testé, pedigree LOOF en main. Pas un jour avant.</span></span>
                </li>
                <li>
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Après</span>
                    <span class="quoi">Nous restons joignables. Un chat né ici qui ne peut plus rester chez vous revient ici, à n'importe quel âge.</span></span>
                </li>
            </ol>
        </div>
    </div>
</section>


<section class="bande">
    <div class="wrap citation monte">
        <?php if (isset($component)) { $__componentOriginal211bc3116c79c8642163dc1f5fe4e306 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal211bc3116c79c8642163dc1f5fe4e306 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fleuron','data' => ['taille' => 'petit','style' => 'color:var(--or-mat)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('fleuron'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['taille' => 'petit','style' => 'color:var(--or-mat)']); ?>
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
        <p>Le prix d'un chaton ne paie pas l'animal. Il paie les neuf mois qui le précèdent.</p>
        <cite>La chatterie</cite>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/home.blade.php ENDPATH**/ ?>