<?php

namespace App\Models\Concerns;

use Illuminate\Support\Collection;

/**
 * Les photos d'une fiche, dans l'ordre d'affichage.
 *
 * Une fiche tire ses images de deux endroits : les colonnes photo_principale
 * et photo_secondaire, remplies des la creation du chat, et la table photos,
 * qui accueille tout ce qui est ajoute ensuite depuis le back-office. Le
 * composant d'affichage n'a pas a connaitre cette difference — il recoit une
 * liste de tableaux identiques, la photo de couverture en tete.
 *
 * Les doublons sont ecartes sur le chemin du fichier : rien n'empeche de
 * televerser dans la galerie une image deja posee en photo principale, et
 * elle apparaitrait alors deux fois dans le ruban de vignettes.
 */
trait AUneGalerie
{
    /** @return Collection<int, array{chemin:string, alt:string, legende:?string}> */
    public function galerie(): Collection
    {
        $depuisLesColonnes = collect([$this->photo_principale, $this->photo_secondaire])
            ->filter()
            ->map(fn (string $chemin) => [
                'chemin'  => $chemin,
                'alt'     => $this->altParDefaut(),
                'legende' => null,
            ]);

        $depuisLaTable = $this->photos
            ->filter(fn ($photo) => $photo->est_publiee)
            ->map(fn ($photo) => [
                'chemin'  => $photo->chemin,
                'alt'     => $photo->alt ?: $this->altParDefaut(),
                'legende' => $photo->legende,
            ]);

        return $depuisLesColonnes
            ->concat($depuisLaTable)
            ->unique('chemin')
            ->values();
    }

    /** Le texte alternatif servi aux images qui n'en portent pas un a elles. */
    abstract public function altParDefaut(): string;
}
