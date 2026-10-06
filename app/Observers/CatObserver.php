<?php

namespace App\Observers;

use App\Exceptions\LigneeRattachee;
use App\Models\Cat;

class CatObserver
{
    /**
     * On n'efface pas un chat rattache a une portee.
     *
     * Le garde-fou vit ici, et non dans l'espace de gestion, pour qu'aucun
     * chemin d'ecriture ne le contourne : une commande, un script de reprise ou
     * un futur ecran passeraient tous par la.
     *
     * Le message nomme les portees concernees. « Suppression impossible » tout
     * court laisserait l'eleveur chercher laquelle, et probablement renoncer a
     * comprendre.
     */
    public function deleting(Cat $chat): void
    {
        if ($chat->peutEtreSupprime()) {
            return;
        }

        $portees = $chat->portees()->pluck('code')->implode(', ');

        throw new LigneeRattachee(
            "« {$chat->nom} » est père ou mère de : {$portees}. "
            .'Effacer sa fiche viderait la ligne « Parents » sur celle de chacun '
            .'de ses chatons, sans que rien ne le signale.'
        );
    }
}
