<?php
/*
    Les plaques d'attente.

    Une photo qui manque affichait jusqu'ici une vignette de developpement :
    fond gris, « PHOTO A FOURNIR » en chasse fixe, le nom du fichier en
    dessous. Sur une page de vente, ca se lit comme un site inacheve.

    A la place, une planche gravee aux couleurs de la charte : la nuit, un
    souffle d'emeraude, l'arche en filet d'or et le fleuron du site au
    centre. Aucun texte — la legende sous l'image dit deja de quoi il s'agit,
    et le grain general du site passe par-dessus.

    Le fond est calcule pixel par pixel sur une toile au tiers, puis agrandi :
    un degrade n'a pas besoin de plus de finesse, et le trait d'or, lui, se
    pose ensuite a pleine definition.
*/

$destination = 'C:/xampp/htdocs/templedesfees/public/images/cats';

/* Les formats suivent ceux des cadres : 7/10 pour une fiche de chaton,
   4/3 pour la figure large d'une portee archivee. Une plaque au mauvais
   format serait recadree, et l'arche s'en trouverait coupee. */
/* Le troisieme nombre decale tres legerement l'arche et le souffle : quatre
   plaques rigoureusement identiques cote a cote se liraient comme un bug. */
$plaques = [
    'chaton-1' => [1200, 1714, 0],
    'chaton-2' => [1200, 1714, 1],
    'chaton-3' => [1200, 1714, 2],
    'chaton-4' => [1200, 1714, 3],
    'portee-b' => [1600, 1200, 1],
];

/*
    imageantialias() de GD ignore le canal alpha : une couleur allouee avec
    imagecolorallocatealpha() s'y dessine en opaque. Un voile « presque
    transparent » devenait donc un aplat noir. Les tons sont par consequent
    melanges a la main, sur la teinte du fond a l'endroit du trait.
*/
function melange(array $teinte, array $fond, float $part): array
{
    return [
        (int) round($teinte[0] * $part + $fond[0] * (1 - $part)),
        (int) round($teinte[1] * $part + $fond[1] * (1 - $part)),
        (int) round($teinte[2] * $part + $fond[2] * (1 - $part)),
    ];
}

/** Une polyligne epaisse, tracee segment par segment. */
function tracer($im, array $points, int $couleur, float $epaisseur): void
{
    imagesetthickness($im, max(1, (int) round($epaisseur)));
    for ($i = 0; $i < count($points) - 1; $i++) {
        imageline(
            $im,
            (int) round($points[$i][0]), (int) round($points[$i][1]),
            (int) round($points[$i + 1][0]), (int) round($points[$i + 1][1]),
            $couleur
        );
    }
    imagesetthickness($im, 1);
}

/**
 * Une feuille : deux arcs qui se rejoignent en pointe. C'est le motif du
 * fleuron du site, repris ici au trait.
 */
function feuille($im, float $cx, float $cy, float $demiLong, float $demiLarge,
                 int $couleur, float $epaisseur, bool $couchee = false,
                 float $pince = 0.44): void
{
    $a = [];
    $b = [];

    for ($i = 0; $i <= 90; $i++) {
        $t  = -1 + 2 * $i / 90;
        $le = $demiLong * $t;
        // L'exposant pince les deux extremites : c'est ce qui fait une
        // feuille plutot qu'une ellipse. Plus il est bas, plus la pointe est
        // fine.
        $la = $demiLarge * pow(max(0.0, 1 - $t * $t), $pince);

        if ($couchee) {
            $a[] = [$cx + $le, $cy - $la];
            $b[] = [$cx + $le, $cy + $la];
        } else {
            $a[] = [$cx - $la, $cy + $le];
            $b[] = [$cx + $la, $cy + $le];
        }
    }

    tracer($im, $a, $couleur, $epaisseur);
    tracer($im, $b, $couleur, $epaisseur);
}

foreach ($plaques as $nom => [$L, $H, $variante]) {

    $ecart = ($variante - 1.5) * 0.018;

    /* ---- le fond, calcule au tiers ---- */
    $l = (int) round($L / 3);
    $h = (int) round($H / 3);
    $fond = imagecreatetruecolor($l, $h);

    for ($y = 0; $y < $h; $y++) {
        $py = $y / ($h - 1);

        for ($x = 0; $x < $l; $x++) {
            $px = $x / ($l - 1);

            // La nuit, un peu plus verte vers le bas.
            $r = 9  + 3 * $py;
            $v = 14 + 10 * $py;
            $b = 12 + 8 * $py;

            // Le souffle d'emeraude, haut a gauche, tres etale.
            $dx = ($px - 0.32 - $ecart) * 1.15;
            $dy = ($py - 0.24 + $ecart) * 0.88;
            $g  = exp(-($dx * $dx + $dy * $dy) / 0.26);
            $r += 4 * $g;  $v += 15 * $g;  $b += 12 * $g;

            // Le vignettage : les bords se ferment, le centre respire.
            $ex = ($px - 0.5) * 2;
            $ey = ($py - 0.5) * 2;
            $vg = 1 - 0.30 * pow(min(1.0, sqrt($ex * $ex * 0.86 + $ey * $ey) / 1.30), 1.9);
            $r *= $vg;  $v *= $vg;  $b *= $vg;

            // Le pied de la planche s'eteint : les cadres du site y posent
            // leur legende, il lui faut un appui.
            if ($py > 0.72) {
                $q = ($py - 0.72) / 0.28;
                $s = 1 - 0.74 * $q * $q;
                $r *= $s;  $v *= $s;  $b *= $s;
            }

            imagesetpixel($fond, $x, $y, imagecolorallocate($fond,
                (int) round($r), (int) round($v), (int) round($b)));
        }
    }

    $im = imagecreatetruecolor($L, $H);
    imagecopyresampled($im, $fond, 0, 0, 0, 0, $L, $H, $l, $h);
    imagedestroy($fond);

    imagealphablending($im, true);
    imageantialias($im, true);

    $centre = [13, 29, 24];
    $orFin = imagecolorallocate($im, ...melange([217, 178, 106], $centre, 0.24));
    $or    = imagecolorallocate($im, ...melange([217, 178, 106], $centre, 0.52));
    $orVif = imagecolorallocate($im, ...melange([246, 231, 191], $centre, 0.74));

    /* ---- le filet interieur ---- */
    imagesetthickness($im, 2);
    $m = (int) round(min($L, $H) * 0.052);
    imagerectangle($im, $m, $m, $L - $m - 1, $H - $m - 1, $orFin);
    imagesetthickness($im, 1);

    /* ---- l'arche ----
       Memes proportions que le cadre .arche du site : un plein cintre pose
       sur deux montants courts, pas un tunnel. */
    $portrait = $H > $L;
    $largeur  = $L * (($portrait ? 0.54 : 0.40) + $ecart * 0.5);
    $cx       = $L / 2;
    $epaule   = $H * ($portrait ? 0.46 : 0.50);
    $pied     = $H * ($portrait ? 0.78 : 0.80);
    $fleche   = $largeur * 0.52;

    $voute = [];
    for ($i = 0; $i <= 260; $i++) {
        $theta = M_PI - M_PI * $i / 260;
        $voute[] = [$cx + ($largeur / 2) * cos($theta), $epaule - $fleche * sin($theta)];
    }

    /* L'arche n'est dessinee que sur la plaque large. Les plaques portrait
       s'affichent DANS le cadre en arche du site : en dessiner une seconde a
       l'interieur donnait deux arches emboitees, ce qui se voit tout de
       suite et ne ressemble a rien. */
    if (! $portrait) {
        tracer($im, $voute, $or, 2.2);
        tracer($im, [[$cx - $largeur / 2, $epaule], [$cx - $largeur / 2, $pied]], $or, 2.2);
        tracer($im, [[$cx + $largeur / 2, $epaule], [$cx + $largeur / 2, $pied]], $or, 2.2);
        tracer($im, [[$cx - $largeur / 2, $pied], [$cx + $largeur / 2, $pied]], $or, 2.2);
    }

    /* ---- le fleuron ----
       Au coeur de l'arche quand il y en a une, au centre de la plaque sinon. */
    $fy = $portrait ? $H * 0.5 : $epaule + ($pied - $epaule) * 0.34;
    $u  = $largeur / ($portrait ? 132 : 112);

    // La feuille centrale et son point.
    feuille($im, $cx, $fy, 12.5 * $u, 4.4 * $u, $orVif, max(1.8, 1.5 * $u), false, 0.34);
    imagefilledellipse($im, (int) round($cx), (int) round($fy),
        (int) round(3.4 * $u), (int) round(3.4 * $u), $orVif);

    // Les deux feuilles couchees, de part et d'autre.
    feuille($im, $cx - 17 * $u, $fy, 12 * $u, 2.6 * $u, $or, max(1.6, 1.2 * $u), true, 0.30);
    feuille($im, $cx + 17 * $u, $fy, 12 * $u, 2.6 * $u, $or, max(1.6, 1.2 * $u), true, 0.30);

    // Les filets qui filent vers les bords, chacun ferme par un point.
    tracer($im, [[$cx - 48 * $u, $fy], [$cx - 30 * $u, $fy]], $or, max(1.4, 1.1 * $u));
    tracer($im, [[$cx + 30 * $u, $fy], [$cx + 48 * $u, $fy]], $or, max(1.4, 1.1 * $u));
    imagefilledellipse($im, (int) round($cx - 28 * $u), (int) round($fy),
        (int) round(2.4 * $u), (int) round(2.4 * $u), $or);
    imagefilledellipse($im, (int) round($cx + 28 * $u), (int) round($fy),
        (int) round(2.4 * $u), (int) round(2.4 * $u), $or);

    // Le point de clef, a la pointe de la voute.
    if (! $portrait) {
        imagefilledellipse($im, (int) round($cx), (int) round($epaule - $fleche),
            (int) round(3.6 * $u), (int) round(3.6 * $u), $orVif);
    }

    imagewebp($im, "$destination/$nom.webp", 86);
    imagedestroy($im);

    printf("  %-10s %4d x %-5d %6.1f ko%s", $nom, $L, $H,
        filesize("$destination/$nom.webp") / 1024, PHP_EOL);
}
