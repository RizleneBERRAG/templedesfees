<?php
    $tel   = \App\Models\Setting::get('contact.telephone', '06 77 35 45 87');
    $telLien = \Illuminate\Support\Str::of($tel)->replace(' ', '')->replaceFirst('0', '+33');
?>


<header class="bandeau" id="bandeau">

    <a class="marque" href="<?php echo e(route('home')); ?>">
        <b class="or">Temple des Fées</b>
        <small>Maine Coon · Drôme</small>
    </a>

    <div class="barre-droite">
        <nav id="menu" aria-label="Navigation principale">
            <a href="<?php echo e(route('kittens.index')); ?>" <?php if(request()->routeIs('kittens.*')): ?> aria-current="page" <?php endif; ?>>Nos chatons</a>
            <a href="<?php echo e(route('cats.index')); ?>"    <?php if(request()->routeIs('cats.*')): ?>    aria-current="page" <?php endif; ?>>Nos chats</a>
            <a href="<?php echo e(route('breed')); ?>"         <?php if(request()->routeIs('breed')): ?>     aria-current="page" <?php endif; ?>>Le Maine Coon</a>
            <a href="<?php echo e(route('adoption.create')); ?>" <?php if(request()->routeIs('adoption.*')): ?> aria-current="page" <?php endif; ?>>Adopter</a>
            <a href="<?php echo e(route('gallery')); ?>"       <?php if(request()->routeIs('gallery')): ?>   aria-current="page" <?php endif; ?>>Galerie</a>
            <a href="<?php echo e(route('faq')); ?>"           <?php if(request()->routeIs('faq')): ?>       aria-current="page" <?php endif; ?>>Questions</a>
            <a href="<?php echo e(route('contact')); ?>"       <?php if(request()->routeIs('contact')): ?>   aria-current="page" <?php endif; ?>>Contact</a>
            <a class="menu-tel" href="tel:<?php echo e($telLien); ?>"><?php echo e($tel); ?></a>
        </nav>

        <a class="tel" href="tel:<?php echo e($telLien); ?>"><?php echo e($tel); ?></a>
        <button class="cle" id="cle" type="button" aria-expanded="false" aria-controls="menu">Menu</button>
    </div>

    
    <span id="jauge" aria-hidden="true"></span>
</header>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/partials/nav.blade.php ENDPATH**/ ?>