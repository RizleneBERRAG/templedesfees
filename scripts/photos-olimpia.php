<?php

/*
    Les photos d'Olimpia.

        php scripts/photos-olimpia.php <photo1> <photo2> ...

    LA PREMIERE EST LA PRINCIPALE : c'est elle qui ouvre le seuil, c'est elle
    qui tient l'arche de sa page, c'est elle qui part en apercu quand on
    partage le lien. Les suivantes viennent apres, dans l'ordre donne.

    Chaque photo sort en trois largeurs, JAMAIS agrandie : si le fichier
    d'origine fait 1080 px, la plus grande fera 1080 px. Agrandir une photo ne
    lui ajoute pas de detail, ca ne fait qu'en etaler le manque.

    Le re-encodage complet supprime au passage les metadonnees EXIF, donc la
    geolocalisation des cliches — l'adresse de l'elevage n'est pas publique.

    Les anciennes sont effacees avant, pour qu'un tirage retire d'une serie ne
    reste pas en ligne tout seul.
*/

$sources = array_slice($argv, 1);

if (! $sources) {
    fwrite(STDERR, "Usage : php scripts/photos-olimpia.php <photo1> <photo2> ...\n");
    fwrite(STDERR, "        La premiere est la principale.\n");
    exit(1);
}

$dossier = __DIR__.'/../public/images/hommage';

if (! is_dir($dossier)) {
    mkdir($dossier, 0777, true);
}

foreach (glob("$dossier/olimpia-[0-9]*.webp") as $vieux) {
    unlink($vieux);
}

$rang = 0;

foreach ($sources as $source) {
    if (! is_file($source)) {
        fwrite(STDERR, "  introuvable, ignoree : $source\n");

        continue;
    }

    $image = @imagecreatefromstring(file_get_contents($source));

    if (! $image) {
        fwrite(STDERR, "  illisible, ignoree : $source\n");

        continue;
    }

    $rang++;
    $l = imagesx($image);
    $h = imagesy($image);

    echo ($rang === 1 ? '  principale ' : '            ')
        .basename($source)." — {$l}×{$h}\n";

    // Jamais au-dessus de la taille native : voir l'en-tete.
    $largeurs = array_values(array_unique(array_filter(
        [min($l, 1400), 800, 400],
        fn ($w) => $w <= $l,
    )));

    foreach ($largeurs as $i => $largeur) {
        $hauteur = (int) round($largeur * $h / $l);

        $sortie = imagecreatetruecolor($largeur, $hauteur);
        imagecopyresampled($sortie, $image, 0, 0, 0, 0, $largeur, $hauteur, $l, $h);

        $nom = $i === 0
            ? "olimpia-$rang.webp"
            : "olimpia-$rang-$largeur.webp";

        imagewebp($sortie, "$dossier/$nom", $largeur <= 400 ? 82 : 88);
        imagedestroy($sortie);

        printf("      %-26s %4d × %4d  %6.0f ko\n", $nom, $largeur, $hauteur,
            filesize("$dossier/$nom") / 1024);
    }

    imagedestroy($image);
}

echo "\n$rang photo(s) en place dans public/images/hommage/.\n";

if ($rang) {
    echo "La principale est olimpia-1.webp.\n";
}
