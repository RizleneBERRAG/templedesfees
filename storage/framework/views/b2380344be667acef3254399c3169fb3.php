

<?php $__env->startSection('title', "Le chat Bengal — robe, caractère, origines"); ?>
<?php $__env->startSection('description', "Tout sur le Bengal avant d'en accueillir un : origines, lecture de la robe (rosettes, glitter, ligne dorsale), motifs, couleurs et caractère réel de la race."); ?>

<?php $__env->startSection('content'); ?>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['niveau' => '1','eyebrow' => 'La race','titre' => 'Le Bengal','lede' => 'Une robe sauvage sur un chat de salon. Ce qu\'il faut savoir avant d\'en accueillir un — y compris ce qui pourrait vous faire changer d\'avis.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['niveau' => '1','eyebrow' => 'La race','titre' => 'Le Bengal','lede' => 'Une robe sauvage sur un chat de salon. Ce qu\'il faut savoir avant d\'en accueillir un — y compris ce qui pourrait vous faire changer d\'avis.']); ?>
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

        <div class="two rev">
            <figure class="figure">
                <img src="<?php echo e(asset('images/cats/wild.webp')); ?>" alt="Bengal adulte, robe brown tabby rosetted">
                <figcaption>Brown tabby rosetted — rosettes, glitter, fond chaud</figcaption>
            </figure>
            <div class="stack">
                <h3>Une race née en Californie</h3>
                <p class="lede">
                    Le Bengal est issu du croisement entre le chat léopard du Bengale
                    (<em class="it">Prionailurus bengalensis</em>) et des chats domestiques, travaillé par
                    Jean S. Mill à partir des années 1960. La race est reconnue par la TICA en 1983 et
                    arrive en France dans les années 1990.
                </p>
                <p class="lede">
                    Les chatons vendus en élevage sont au minimum de quatrième génération : ce sont des
                    chats domestiques à part entière, sans aucune restriction réglementaire.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="band ink2">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Lire une robe','titre' => 'Cinq points qu\'un juge regarde','lede' => 'Cliquez sur les repères pour comprendre le vocabulaire qu\'on utilise dans les fiches de nos chatons.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Lire une robe','titre' => 'Cinq points qu\'un juge regarde','lede' => 'Cliquez sur les repères pour comprendre le vocabulaire qu\'on utilise dans les fiches de nos chatons.']); ?>
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

        <?php ($reperes = config('chatterie.morphologie')); ?>

        <div style="max-width:940px">
            <div class="coat" id="coat" data-points="<?php echo e(json_encode(collect($reperes)->map(fn ($r) => ['k' => $r['categorie'], 't' => $r['titre'], 'd' => $r['texte']]), JSON_UNESCAPED_UNICODE)); ?>">
                <img src="<?php echo e(asset('images/cats/uanna.webp')); ?>" alt="Robe de Bengal brown tabby rosetted, détail des rosettes">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $reperes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button class="hot" type="button"
                            style="left:<?php echo e($r['x']); ?>%;top:<?php echo e($r['y']); ?>%"
                            data-coat="<?php echo e($i); ?>"
                            aria-pressed="<?php echo e($i === 0 ? 'true' : 'false'); ?>"
                            aria-label="<?php echo e($r['titre']); ?>"><span class="ring"></span></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="coatinfo" id="coatinfo">
                
                <span class="k"><?php echo e($reperes[0]['categorie']); ?></span>
                <h3><?php echo e($reperes[0]['titre']); ?></h3>
                <p><?php echo e($reperes[0]['texte']); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="band">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Les motifs','titre' => 'Spotted, rosetted, marbled','lede' => 'Trois familles de motifs, et une couleur de fond qui change tout.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Les motifs','titre' => 'Spotted, rosetted, marbled','lede' => 'Trois familles de motifs, et une couleur de fond qui change tout.']); ?>
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
            <div class="cell-b"><span class="n">MOTIF</span><h3>Spotted</h3><p>Des taches pleines, d'un seul ton, réparties horizontalement. C'est le motif le plus courant, et la base de tous les autres.</p></div>
            <div class="cell-b"><span class="n">MOTIF</span><h3>Rosetted</h3><p>Chaque tache est cerclée d'un contour plus foncé. Rosette en flèche, en patte d'ours, en donut : plus le cercle est fermé, plus le travail de sélection est abouti.</p></div>
            <div class="cell-b"><span class="n">MOTIF</span><h3>Marbled</h3><p>De grands aplats horizontaux en marbrures, sans alignement vertical ni motif en cible. Plus rare dans nos portées.</p></div>
            <div class="cell-b"><span class="n">COULEUR</span><h3>Brown, snow, silver, charcoal</h3><p>Du fond doré classique au snow aux yeux aqua, en passant par le silver et le masque charcoal. Une même portée peut en contenir plusieurs.</p></div>
        </div>
    </div>
</section>

<div class="band tight" style="padding-block:clamp(22px,3vw,36px)">
    <?php if (isset($component)) { $__componentOriginal88b2be7e5b1343afa0d4e3e348532adf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal88b2be7e5b1343afa0d4e3e348532adf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-strip','data' => ['titre' => 'Robes et motifs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titre' => 'Robes et motifs']); ?>
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

<section class="band paper">
    <div class="wrap">
        <?php if (isset($component)) { $__componentOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf396d8ec30310aa6ec0c9d9f36fca53d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-head','data' => ['eyebrow' => 'Le caractère','titre' => 'Ce n\'est pas un chat tranquille','lede' => 'Autant le dire tout de suite : si vous cherchez un chat qui dort seize heures par jour sur un radiateur, le Bengal n\'est pas fait pour vous.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Le caractère','titre' => 'Ce n\'est pas un chat tranquille','lede' => 'Autant le dire tout de suite : si vous cherchez un chat qui dort seize heures par jour sur un radiateur, le Bengal n\'est pas fait pour vous.']); ?>
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
            <div class="cell-b"><h3>Il parle</h3><p>Beaucoup. Il commente vos déplacements, réclame, et répond quand on lui adresse la parole. C'est charmant les six premiers mois, et il faut aimer ça.</p></div>
            <div class="cell-b"><h3>Il grimpe</h3><p>Prévoyez de la hauteur : arbre à chat solide, étagères libérées, et acceptez qu'il soit souvent au-dessus de vous, y compris sur le réfrigérateur.</p></div>
            <div class="cell-b"><h3>Il joue avec l'eau</h3><p>Robinet, douche, gamelle renversée. C'est une constante de la race, pas une excentricité individuelle.</p></div>
            <div class="cell-b"><h3>Il vit longtemps</h3><p>Douze à seize ans en bonne santé. C'est un engagement sur une durée, pas une décoration d'intérieur.</p></div>
        </div>
        <div class="btnrow" style="margin-top:36px">
            <a class="btn" href="<?php echo e(route('kittens.index')); ?>">Voir les chatons disponibles</a>
            <a class="btn ghost" href="<?php echo e(route('faq')); ?>">Questions fréquentes</a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/pages/breed.blade.php ENDPATH**/ ?>