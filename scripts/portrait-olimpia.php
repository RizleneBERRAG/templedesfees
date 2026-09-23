<?php

/*
    Le portrait d'Olimpia.

    La photo fournie par l'eleveur est un JPEG de telephone. On la passe en
    WebP aux trois tailles du site (1200 / 800 / 400) avec la meme chaine que
    les autres images : cadrage sur la hauteur utile, poids divise par cinq,
    et surtout re-encodage complet — qui supprime au passage les metadonnees
    EXIF, donc la geolocalisation du cliche.

        php scripts/portrait-olimpia.php <source.jpg>

    A relancer si l'eleveur envoie une meilleure photo.
*/

$source = $argv[1] ?? null;

if (! $source || ! is_file($source)) {
    fwrite(STDERR, "Usage : php scripts/portrait-olimpia.php <fichier.jpg>\n");
    exit(1);
}

$dossier = __DIR__.'/../public/images/hommage';

if (! is_dir($dossier)) {
    mkdir($dossier, 0777, true);
}

$image = imagecreatefromstring(file_get_contents($source));

if (! $image) {
    fwrite(STDERR, "Image illisible.\n");
    exit(1);
}

$l = imagesx($image);
$h = imagesy($image);

/*
    Le portrait s'affiche dans une arche, en 7/10. Une photo de telephone est
    plus etroite que cela : on recadre sur la largeur utile en gardant le haut,
    la ou sont la tete et les oreilles.
*/
$rapport = 7 / 10;
$hCible  = $h;
$lCible  = (int) round($h * $rapport);

if ($lCible > $l) {
    $lCible = $l;
    $hCible = (int) round($l / $rapport);
}

$x = (int) round(($l - $lCible) / 2);
$y = 0;                                  // on garde le haut : la tete d'abord

foreach ([1200, 800, 400] as $largeur) {
    $hauteur = (int) round($largeur / $rapport);

    $sortie = imagecreatetruecolor($largeur, $hauteur);
    imagecopyresampled($sortie, $image, 0, 0, $x, $y, $largeur, $hauteur, $lCible, $hCible);

    $nom = $largeur === 1200 ? 'olimpia.webp' : "olimpia-{$largeur}.webp";
    imagewebp($sortie, "$dossier/$nom", $largeur === 400 ? 82 : 86);
    imagedestroy($sortie);

    printf("  %-18s %4d × %4d  %6.0f ko\n", $nom, $largeur, $hauteur, filesize("$dossier/$nom") / 1024);
}

imagedestroy($image);

echo "\nPortrait en place dans public/images/hommage/.\n";
