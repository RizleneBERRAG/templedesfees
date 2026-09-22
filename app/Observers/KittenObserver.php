<?php

namespace App\Observers;

use App\Models\Kitten;

/**
 * Garde-fou : un chaton dont il manque le numero ICAD ou le numero de portee LOOF
 * ne peut pas etre publie, meme si quelqu'un coche la case dans le back-office.
 * La fiche repasse silencieusement en brouillon.
 */
class KittenObserver
{
    public function saving(Kitten $kitten): void
    {
        if ($kitten->est_publie && ! $kitten->estPubliable()) {
            $kitten->est_publie = false;
        }
    }
}
