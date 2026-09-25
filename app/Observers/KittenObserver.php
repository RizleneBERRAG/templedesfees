<?php

namespace App\Observers;

use App\Exceptions\FactureEmise;
use App\Models\Kitten;

/**
 * Les deux garde-fous d'une fiche chaton.
 *
 * Le premier : un chaton dont il manque le numero ICAD ou le numero de portee
 * LOOF ne peut pas etre publie, meme si quelqu'un coche la case dans le
 * back-office. La fiche repasse silencieusement en brouillon.
 *
 * Le second : une fiche dont l'acompte a ete encaisse ne s'efface pas. La
 * cle etrangere est en cascade, donc la supprimer emporterait la reservation,
 * son numero de facture et la somme recue. Le refus est pose ici plutot que
 * dans le back-office pour qu'aucun chemin d'ecriture n'y echappe — action
 * groupee, commande artisan, console.
 */
class KittenObserver
{
    public function saving(Kitten $kitten): void
    {
        if ($kitten->est_publie && ! $kitten->estPubliable()) {
            $kitten->est_publie = false;
        }
    }

    public function deleting(Kitten $kitten): void
    {
        if ($kitten->peutEtreSupprime()) {
            return;
        }

        throw new FactureEmise(
            "Le chaton « {$kitten->nom} » porte une réservation encaissée : "
            .'sa facture et le montant reçu seraient effacés avec la fiche.'
        );
    }
}
