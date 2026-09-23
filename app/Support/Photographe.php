<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Le photographe.
 *
 * Tout ce qui entre par le back-office lui passe entre les mains avant d'etre
 * range dans public/images/cats. Il fait quatre choses, dans cet ordre :
 *
 * 1. Il redresse. Une photo prise au telephone arrive souvent couchee : son
 *    orientation ne vit pas dans les pixels mais dans une etiquette EXIF que
 *    tous les navigateurs ne lisent pas. On tourne donc les pixels pour de bon.
 *
 * 2. Il efface les metadonnees. C'est le point le plus important, et le moins
 *    visible : une photo de telephone porte les coordonnees GPS de l'endroit
 *    ou elle a ete prise. L'elevage ne publie pas son adresse — elle est
 *    communiquee au rendez-vous — et la publierait pourtant dans chaque
 *    photo de chaton. Le reencodage par GD ne recopie aucune etiquette :
 *    position, modele d'appareil, date, tout disparait.
 *
 * 3. Il plafonne a 1200 px. Au-dela, personne ne voit la difference sur le
 *    site, et la visionneuse plein ecran n'affiche jamais plus large.
 *
 * 4. Il fabrique les deux reductions, en 800 et 400 px, que <x-img> declare
 *    en srcset. Sans elles, une photo ajoutee par l'eleveuse serait servie
 *    en pleine taille dans une vignette de 74 px.
 *
 * Le resultat est toujours un WebP : c'est deux a trois fois plus leger qu'un
 * JPEG a qualite egale, et c'est la convention des 36 photos posees au depart.
 */
class Photographe
{
    /** La plus grande dimension conservee, en pixels. */
    private const PLAFOND = 1200;

    /** Les reductions fabriquees a cote de l'originale. */
    private const REDUCTIONS = [800, 400];

    /**
     * Range un fichier envoye et renvoie son chemin relatif a public/,
     * « images/cats/tika.webp » — la forme qu'attendent les colonnes et
     * asset().
     */
    public static function ranger(UploadedFile $fichier, string $dossier = 'images/cats'): string
    {
        $image = self::ouvrir($fichier);

        if (! $image) {
            // Format que GD ne sait pas lire : on range tel quel plutot que de
            // perdre la photo. Elle sera simplement servie sans reduction.
            return $fichier->store($dossier, 'site');
        }

        $image = self::redresser($image, $fichier->getRealPath());
        $image = self::plafonner($image, self::PLAFOND);

        $nom = self::nomLibre($dossier, $fichier->getClientOriginalName());
        $racine = public_path("$dossier/$nom");

        imagewebp($image, "$racine.webp", 86);

        foreach (self::REDUCTIONS as $large) {
            if (imagesx($image) <= $large) {
                continue;
            }

            $petite = self::plafonner($image, $large, copie: true);
            imagewebp($petite, "$racine-$large.webp", 82);
            imagedestroy($petite);
        }

        imagedestroy($image);

        return "$dossier/$nom.webp";
    }

    /** Ouvre le fichier envoye, quel que soit son format d'origine. */
    private static function ouvrir(UploadedFile $fichier): \GdImage|false
    {
        return match ($fichier->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($fichier->getRealPath()),
            'image/png'  => @imagecreatefrompng($fichier->getRealPath()),
            'image/webp' => @imagecreatefromwebp($fichier->getRealPath()),
            default      => false,
        };
    }

    /**
     * Remet la photo d'aplomb.
     *
     * Les huit orientations EXIF se ramenent a trois rotations et deux
     * miroirs. On ne traite que les cas qu'un telephone produit reellement.
     */
    private static function redresser(\GdImage $image, string $chemin): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($chemin);
        $orientation = $exif['Orientation'] ?? 1;

        $angle = match ($orientation) {
            3, 4 => 180,
            5, 6 => -90,
            7, 8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $tourne = imagerotate($image, $angle, 0);
            imagedestroy($image);
            $image = $tourne;
        }

        // 2, 4, 5 et 7 sont en plus des miroirs.
        if (in_array($orientation, [2, 4, 5, 7], true)) {
            imageflip($image, IMG_FLIP_HORIZONTAL);
        }

        return $image;
    }

    /** Reduit la photo pour que sa plus grande dimension tienne dans $cote. */
    private static function plafonner(\GdImage $image, int $cote, bool $copie = false): \GdImage
    {
        [$L, $H] = [imagesx($image), imagesy($image)];
        $facteur = $cote / max($L, $H);

        if ($facteur >= 1 && ! $copie) {
            return $image;
        }

        $facteur = min(1, $facteur);
        $l = max(1, (int) round($L * $facteur));
        $h = max(1, (int) round($H * $facteur));

        $petite = imagecreatetruecolor($l, $h);
        imagealphablending($petite, false);
        imagesavealpha($petite, true);
        imagecopyresampled($petite, $image, 0, 0, 0, 0, $l, $h, $L, $H);

        if (! $copie) {
            imagedestroy($image);
        }

        return $petite;
    }

    /**
     * Un nom de fichier libre, tire du nom d'origine.
     *
     * On garde le nom choisi par l'eleveuse — « tika-hiver » reste
     * « tika-hiver » — parce qu'elle le reconnaitra dans la liste des photos.
     * Un suffixe n'arrive qu'en cas de collision.
     */
    private static function nomLibre(string $dossier, string $nomOrigine): string
    {
        $base = Str::slug(pathinfo($nomOrigine, PATHINFO_FILENAME)) ?: 'photo';
        $nom = $base;
        $n = 2;

        while (file_exists(public_path("$dossier/$nom.webp"))) {
            $nom = "$base-$n";
            $n++;
        }

        return $nom;
    }

    /**
     * Efface une photo et ses reductions.
     *
     * Sans cela, changer la photo d'une fiche laisse derriere elle deux
     * fichiers que plus personne ne sert et que personne ne voit.
     */
    public static function effacer(?string $chemin): void
    {
        if (! $chemin || ! str_ends_with($chemin, '.webp')) {
            return;
        }

        $base = public_path(Str::beforeLast($chemin, '.webp'));

        foreach (['', ...array_map(fn ($t) => "-$t", self::REDUCTIONS)] as $suffixe) {
            $fichier = "$base$suffixe.webp";

            if (is_file($fichier)) {
                @unlink($fichier);
            }
        }
    }
}
