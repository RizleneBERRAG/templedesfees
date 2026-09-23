<?php

/*
    Les vignettes.

    Les photos de chats font 1200 px de large et pesent entre 180 et 250 ko.
    Elles sont servies telles quelles partout, y compris dans une vignette de
    120 px sur une carte d'article : le visiteur telecharge dix fois ce qu'il
    voit. Sur la galerie, onze photos pleine taille font 2,4 Mo.

    Ce script fabrique deux reductions a cote de chaque original :

        tika.webp        1200 px   l'original, pour la vue plein ecran
        tika-800.webp     800 px   les grandes fiches
        tika-400.webp     400 px   les cartes et les vignettes

    Le composant <x-img> s'en sert pour ecrire un srcset : le navigateur
    choisit alors la taille qu'il lui faut, en tenant compte de la densite de
    l'ecran. Rien n'est perdu sur un ecran Retina, tout est gagne ailleurs.

    Relancer apres chaque ajout de photo :
        C:\xampp\php\php.exe scripts/vignettes.php
*/

$dossier = 'public/images/cats';
$tailles = [800, 400];

$originaux = array_filter(
    glob("$dossier/*.webp"),
    fn ($f) => ! preg_match('/-\d+\.webp$/', $f)
);

if (! $originaux) {
    fwrite(STDERR, "Aucune photo dans $dossier\n");
    exit(1);
}

$avant = 0;
$apres = 0;
$faites = 0;

foreach ($originaux as $source) {
    $nom = pathinfo($source, PATHINFO_FILENAME);
    $src = imagecreatefromwebp($source);

    if (! $src) {
        fwrite(STDERR, "Illisible : $source\n");
        continue;
    }

    [$L, $H] = [imagesx($src), imagesy($src)];
    $avant += filesize($source);

    foreach ($tailles as $large) {
        // Une photo plus petite que la cible ne se reagrandit pas : ce serait
        // un fichier plus lourd pour une image moins bonne.
        if ($L <= $large) {
            continue;
        }

        $haut = (int) round($H * $large / $L);
        $im = imagecreatetruecolor($large, $haut);
        imagealphablending($im, false);
        imagesavealpha($im, true);
        imagecopyresampled($im, $src, 0, 0, 0, 0, $large, $haut, $L, $H);

        $cible = "$dossier/$nom-$large.webp";
        imagewebp($im, $cible, 82);
        imagedestroy($im);

        $apres += filesize($cible);
        $faites++;
    }

    imagedestroy($src);
}

printf("  %d vignettes pour %d photos%s", $faites, count($originaux), PHP_EOL);
printf("  originaux %s  ·  vignettes %s%s",
    number_format($avant / 1024, 0, ',', ' ').' ko',
    number_format($apres / 1024, 0, ',', ' ').' ko',
    PHP_EOL);
