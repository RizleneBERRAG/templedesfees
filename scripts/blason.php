<?php

/*
    Le blason.

    Prend le logo fourni — une tete de Maine Coon au trait, blanche sur noir —
    et en tire tout ce dont le site a besoin :

      images/blason.png        le trait dans la dorure de la charte, sur fond
                               transparent, pour le bandeau et le pied de page
      images/blason-32.png     l'icone d'onglet
      images/blason-192.png    l'icone d'application
      images/blason-180.png    l'icone d'ecran d'accueil iOS, sur la nuit
                               (iOS ne gere pas la transparence et collerait
                               un fond blanc)

    Le noir du fichier d'origine n'est pas un fond : c'est l'absence de trait.
    On s'en sert donc comme couche de transparence — la luminance de chaque
    pixel devient son opacite. Le trait garde ainsi ses degrades et ses pointes
    fines, ce qu'un seuillage aurait hache.

    Usage :
        C:\xampp\php\php.exe scripts/blason.php public/images/logo-source.png
*/

$source = $argv[1] ?? 'public/images/logo-source.png';

if (! is_file($source)) {
    fwrite(STDERR, "Fichier introuvable : $source\n");
    fwrite(STDERR, "Déposez le logo puis relancez, par exemple :\n");
    fwrite(STDERR, "  C:\\xampp\\php\\php.exe scripts/blason.php public/images/logo-source.png\n");
    exit(1);
}

$info = getimagesize($source);
$src = match ($info[2]) {
    IMAGETYPE_PNG  => imagecreatefrompng($source),
    IMAGETYPE_JPEG => imagecreatefromjpeg($source),
    IMAGETYPE_WEBP => imagecreatefromwebp($source),
    default        => null,
};

if (! $src) {
    fwrite(STDERR, "Format non géré : {$info['mime']}\n");
    exit(1);
}

[$L, $H] = [imagesx($src), imagesy($src)];

/*
    Le cadrage.

    Le fichier d'origine porte une large marge noire. On cherche la boite du
    trait — le premier pixel non noir de chaque bord — puis on la reprend avec
    une marge egale, pour que le blason ne colle pas au bord de son cadre.
*/
$seuil = 24;
$x0 = $L; $y0 = $H; $x1 = 0; $y1 = 0;

for ($y = 0; $y < $H; $y++) {
    for ($x = 0; $x < $L; $x++) {
        $c = imagecolorat($src, $x, $y);
        $lum = (($c >> 16 & 255) * 299 + ($c >> 8 & 255) * 587 + ($c & 255) * 114) / 1000;

        if ($lum > $seuil) {
            if ($x < $x0) $x0 = $x;
            if ($y < $y0) $y0 = $y;
            if ($x > $x1) $x1 = $x;
            if ($y > $y1) $y1 = $y;
        }
    }
}

if ($x1 <= $x0 || $y1 <= $y0) {
    fwrite(STDERR, "Aucun trait trouvé : l'image est-elle bien claire sur fond sombre ?\n");
    exit(1);
}

$largeurTrait = $x1 - $x0 + 1;
$hauteurTrait = $y1 - $y0 + 1;
$cote = (int) round(max($largeurTrait, $hauteurTrait) * 1.10);   // 5 % de marge de chaque cote

/*
    La planche de reference, en 1024 : le trait centre sur un carre
    transparent, chaque pixel prenant sa luminance pour opacite et la dorure
    pour couleur.
*/
$N = 1024;
$blason = imagecreatetruecolor($N, $N);
imagealphablending($blason, false);
imagesavealpha($blason, true);
imagefill($blason, 0, 0, imagecolorallocatealpha($blason, 0, 0, 0, 127));

// La dorure de la charte : clair en haut, plein au milieu, ombre en bas.
$arrets = [[0.0, [251, 243, 221]], [0.44, [229, 200, 140]], [1.0, [180, 143, 72]]];

$dorure = function (float $t) use ($arrets): array {
    for ($i = 0; $i < count($arrets) - 1; $i++) {
        [$a, $ca] = $arrets[$i];
        [$b, $cb] = $arrets[$i + 1];

        if ($t <= $b || $i === count($arrets) - 2) {
            $p = $b > $a ? min(1, max(0, ($t - $a) / ($b - $a))) : 0;

            return [
                (int) round($ca[0] + ($cb[0] - $ca[0]) * $p),
                (int) round($ca[1] + ($cb[1] - $ca[1]) * $p),
                (int) round($ca[2] + ($cb[2] - $ca[2]) * $p),
            ];
        }
    }

    return $arrets[0][1];
};

$cx = ($x0 + $x1) / 2;
$cy = ($y0 + $y1) / 2;

for ($y = 0; $y < $N; $y++) {
    // La couleur ne suit pas le pixel mais la hauteur dans le blason : c'est
    // ce qui fait un degrade continu plutot qu'un or tachete.
    [$r, $v, $b] = $dorure($y / ($N - 1));

    for ($x = 0; $x < $N; $x++) {
        $sx = (int) round($cx + ($x / $N - 0.5) * $cote);
        $sy = (int) round($cy + ($y / $N - 0.5) * $cote);

        if ($sx < 0 || $sy < 0 || $sx >= $L || $sy >= $H) {
            continue;
        }

        $c = imagecolorat($src, $sx, $sy);
        $lum = (($c >> 16 & 255) * 299 + ($c >> 8 & 255) * 587 + ($c & 255) * 114) / 1000;

        if ($lum < 3) {
            continue;
        }

        $alpha = 127 - (int) round(min(255, $lum) * 127 / 255);
        imagesetpixel($blason, $x, $y, imagecolorallocatealpha($blason, $r, $v, $b, $alpha));
    }
}

imagedestroy($src);

$dossier = 'public/images';

$reduire = function (int $taille, string $nom, ?array $fond = null) use ($blason, $N, $dossier) {
    $im = imagecreatetruecolor($taille, $taille);
    imagealphablending($im, false);
    imagesavealpha($im, true);

    if ($fond) {
        imagealphablending($im, true);
        imagefilledrectangle($im, 0, 0, $taille, $taille,
            imagecolorallocate($im, ...$fond));
    } else {
        imagefill($im, 0, 0, imagecolorallocatealpha($im, 0, 0, 0, 127));
        imagealphablending($im, false);
    }

    imagealphablending($im, true);
    imagecopyresampled($im, $blason, 0, 0, 0, 0, $taille, $taille, $N, $N);
    imagesavealpha($im, true);

    imagepng($im, "$dossier/$nom", 9);
    imagedestroy($im);

    printf("  %-20s %4d x %-4d %6.1f ko%s", $nom, $taille, $taille,
        filesize("$dossier/$nom") / 1024, PHP_EOL);
};

$reduire(512, 'blason.png');
$reduire(192, 'blason-192.png');
$reduire(32,  'blason-32.png');
$reduire(180, 'blason-180.png', [8, 11, 10]);   // iOS ne gère pas la transparence

imagedestroy($blason);

echo PHP_EOL, "  Le blason est en place. Il s'affiche désormais dans le bandeau,", PHP_EOL;
echo "  dans le pied de page et comme icône d'onglet.", PHP_EOL;
