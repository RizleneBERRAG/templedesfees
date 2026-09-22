<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title><?php echo $__env->yieldContent('title', "Chatterie du Temple des Fées"); ?> — Élevage de Maine Coon dans la Drôme</title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', "Chatterie familiale de Maine Coon à Lapeyrouse-Mornay (26), Drôme des collines. Chatons inscrits au LOOF, parents dépistés HCM, SMA et PK-Def, résultats publiés."); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Chatterie du Temple des Fées">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', "Chatterie du Temple des Fées"); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('description', "Élevage familial de Maine Coon dans la Drôme. Parents dépistés, résultats publiés."); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('images/cats/hero-duo.webp')); ?>">
    <meta name="twitter:card" content="summary_large_image">

    
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="<?php echo e(asset('fonts/cormorant-garamond-normal-300-latin.woff2')); ?>">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="<?php echo e(asset('fonts/cinzel-normal-400-600-latin.woff2')); ?>">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="<?php echo e(asset('fonts/jost-normal-300-500-latin.woff2')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fonts.css')); ?>">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
    <?php echo $__env->yieldPushContent('schema'); ?>
</head>
<body>

<?php echo $__env->make('partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<main id="app">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\templedesfees\resources\views/layouts/app.blade.php ENDPATH**/ ?>