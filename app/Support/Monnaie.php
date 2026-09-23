<?php

namespace App\Support;

/**
 * Les sommes.
 *
 * Tout ce qui est de l'argent est stocke en centimes, en entier : un montant
 * en flottant finit toujours par produire un 199,99 la ou on avait tape 200,
 * et on ne discute pas d'un centime avec quelqu'un qui vient de payer.
 *
 * Reste a l'ecrire comme on l'ecrit en francais : espace insecable avant
 * l'euro, virgule decimale, et pas de « ,00 » quand la somme est ronde.
 */
final class Monnaie
{
    public static function euros(?int $centimes): string
    {
        if ($centimes === null) {
            return '—';
        }

        $euros = $centimes / 100;

        return number_format($euros, fmod($euros, 1) === 0.0 ? 0 : 2, ',', ' ')."\u{00A0}€";
    }

    /** Une saisie en euros (« 1 500 », « 1500,50 ») vers des centimes. */
    public static function centimes(mixed $saisie): int
    {
        $propre = str_replace([' ', "\u{00A0}", "\u{202F}", '€'], '', (string) $saisie);

        return (int) round(((float) str_replace(',', '.', $propre)) * 100);
    }
}
