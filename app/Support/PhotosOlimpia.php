<?php

namespace App\Support;

/**
 * Les photos d'Olimpia, dans l'ordre.
 *
 * Elles vivent dans public/images/hommage, numerotees, posees par
 * scripts/photos-olimpia.php avec un manifeste qui dit ce que chacune mesure.
 *
 * La premiere est la principale : c'est elle qui ouvre le seuil, qui tient
 * l'arche de sa page, et qui part en apercu quand on partage le lien.
 *
 * On lit le manifeste plutot que le dossier : le seuil est inclus dans la mise
 * en page, donc present sur toutes les pages du site, et ouvrir cinq fichiers
 * a chaque requete pour en connaitre les dimensions serait cher pour rien. Le
 * resultat est garde le temps de la requete.
 */
final class PhotosOlimpia
{
    private const DOSSIER = 'images/hommage';

    /**
     * En dessous de mille pixels de large, une photo tient dans une planche de
     * tirages mais pas en plein ecran : etalee, elle ne montre plus que son
     * agrandissement.
     */
    private const LARGEUR_PLEIN_ECRAN = 1000;

    /** @var array<int, array{chemin: string, largeur: int, hauteur: int}>|null */
    private static ?array $liste = null;

    /** @return array<int, array{chemin: string, largeur: int, hauteur: int}> */
    public static function manifeste(): array
    {
        if (self::$liste !== null) {
            return self::$liste;
        }

        $fichier = public_path(self::DOSSIER.'/olimpia.json');

        if (! is_file($fichier)) {
            return self::$liste = [];
        }

        $brut = json_decode(file_get_contents($fichier), true);

        if (! is_array($brut)) {
            return self::$liste = [];
        }

        return self::$liste = array_values(array_map(
            fn (array $p) => [
                'chemin'  => self::DOSSIER.'/'.$p['fichier'],
                'largeur' => (int) ($p['largeur'] ?? 0),
                'hauteur' => (int) ($p['hauteur'] ?? 0),
            ],
            array_filter($brut, fn ($p) => is_array($p) && filled($p['fichier'] ?? null)),
        ));
    }

    /**
     * Toutes ses photos, la principale d'abord.
     *
     * @return array<int, string> des chemins relatifs a public/
     */
    public static function toutes(): array
    {
        return array_column(self::manifeste(), 'chemin');
    }

    /** La principale, ou null tant qu'aucune photo n'a ete deposee. */
    public static function principale(): ?string
    {
        return self::toutes()[0] ?? null;
    }

    /** Les autres, celles qui accompagnent le recit. */
    public static function suivantes(): array
    {
        return array_slice(self::toutes(), 1);
    }

    /**
     * Celles qui passent en fondu derriere le seuil.
     *
     * La principale y est toujours, meme petite : c'est elle qui ouvre, et on
     * ne la remplace pas pour une question de pixels. Les autres doivent tenir
     * le plein ecran.
     *
     * @return array<int, string>
     */
    public static function pourLeSeuil(): array
    {
        $retenues = [];

        foreach (self::manifeste() as $rang => $photo) {
            if ($rang === 0 || $photo['largeur'] >= self::LARGEUR_PLEIN_ECRAN) {
                $retenues[] = $photo['chemin'];
            }
        }

        return $retenues;
    }

    public static function combien(): int
    {
        return count(self::manifeste());
    }
}
