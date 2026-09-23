<?php

namespace App\Support;

/**
 * Les photos d'Olimpia, dans l'ordre.
 *
 * Elles vivent dans public/images/hommage, numerotees, posees par
 * scripts/photos-olimpia.php. La premiere est la principale : c'est elle qui
 * ouvre le seuil, qui tient l'arche de sa page, et qui part en apercu quand on
 * partage le lien.
 *
 * On les lit sur le disque plutot que dans une table : ce sont cinq fichiers
 * qui ne bougeront plus, et il serait absurde de demander a l'eleveuse de les
 * declarer quelque part apres les avoir deposees.
 *
 * Le resultat est mis en cache pour la duree de la requete : le seuil est
 * inclus dans la mise en page, donc sur toutes les pages du site, et il serait
 * dommage d'aller frapper le disque a chaque fois.
 */
final class PhotosOlimpia
{
    private const DOSSIER = 'images/hommage';

    /** @var array<int, string>|null */
    private static ?array $liste = null;

    /**
     * Toutes ses photos, la principale d'abord.
     *
     * @return array<int, string> des chemins relatifs a public/
     */
    public static function toutes(): array
    {
        if (self::$liste !== null) {
            return self::$liste;
        }

        $trouvees = glob(public_path(self::DOSSIER).'/olimpia-[0-9].webp') ?: [];

        // glob trie par nom : olimpia-1, olimpia-2… L'ordre des fichiers est
        // donc l'ordre voulu, et il n'y a rien de plus a trier.
        return self::$liste = array_map(
            fn (string $chemin) => self::DOSSIER.'/'.basename($chemin),
            $trouvees,
        );
    }

    /** La principale, ou null tant qu'aucune photo n'a ete deposee. */
    public static function principale(): ?string
    {
        return self::toutes()[0] ?? null;
    }

    /** Les autres, celles qui accompagnent. */
    public static function suivantes(): array
    {
        return array_slice(self::toutes(), 1);
    }

    public static function combien(): int
    {
        return count(self::toutes());
    }
}
