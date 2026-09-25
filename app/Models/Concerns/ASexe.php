<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Le sexe d'un animal, sous ses deux formes.
 *
 * Il en faut deux, et il a fallu s'en apercevoir trois fois.
 *
 * La forme enregistree n'a pas d'accent : « male ». C'est elle qui va dans
 * l'adresse du filtre, dans le menu deroulant du back-office et dans la
 * comparaison. La base a longtemps porte les deux — « male » pour ce qui
 * venait du back-office, « male » accentue pour ce qui venait du seed — et
 * chacune des deux formes a casse quelque chose de son cote :
 *
 *   — le filtre ?sexe=male ne trouvait rien, et le lien « Males » n'etait
 *     meme pas rendu, faute de compte ;
 *   — ouvrir la fiche d'un male dans le back-office montrait un menu vide,
 *     et l'enregistrer echouait sur un champ auquel on n'avait pas touche ;
 *   — le site affichait « Male » pour les uns et « Male » accentue pour les
 *     autres, selon l'origine de la fiche.
 *
 * L'accent appartient donc a l'affichage, et a lui seul. sexeEnAdresse()
 * reste tolerant : il accepte l'ancienne forme accentuee, pour qu'une base
 * qui n'aurait pas recu la migration ne reproduise pas le defaut.
 */
trait ASexe
{
    /** Ce qu'on enregistre, ce qu'on compare, ce qu'on met dans une adresse. */
    public function sexeEnAdresse(): string
    {
        return (string) Str::of((string) $this->sexe)->lower()->ascii();
    }

    /** Ce qu'on ecrit a l'ecran, accent compris. */
    public function sexeLibelle(): string
    {
        return match ($this->sexeEnAdresse()) {
            'male'    => 'Mâle',
            'femelle' => 'Femelle',
            default   => (string) $this->sexe,
        };
    }
}
