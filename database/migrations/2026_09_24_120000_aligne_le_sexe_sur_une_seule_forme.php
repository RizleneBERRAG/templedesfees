<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Le sexe s'ecrivait de deux facons dans la meme base.
 *
 * « male » accentue pour ce qui venait du seed, « male » sans accent pour ce
 * qui venait du back-office, dont le menu deroulant ne proposait que la
 * seconde forme. Les deux cohabitaient : au moment d'ecrire cette migration,
 * les chatons portaient un « male » accentue et trois « male » nus.
 *
 * Trois choses en decoulaient, et aucune ne se voyait a la lecture du code :
 *
 *   1. le filtre public ?sexe=male ne trouvait rien, et le lien « Males »
 *      n'etait meme pas rendu, faute de compte ;
 *   2. ouvrir la fiche d'un male dans le back-office montrait un menu vide,
 *      et l'enregistrer echouait sur un champ auquel on n'avait pas touche —
 *      l'eleveuse ne pouvait donc plus modifier la description d'un male ;
 *   3. le site ecrivait « Male » pour les uns et « Male » accentue pour les
 *      autres, selon l'origine de la fiche.
 *
 * La forme enregistree perd donc son accent, une fois pour toutes. L'accent
 * appartient a l'affichage : c'est sexeLibelle() qui le remet.
 */
return new class extends Migration
{
    /** Ce qu'on a trouve dans la base, et ce qu'on veut a la place. */
    private const CORRESPONDANCES = [
        'mâle'    => 'male',
        'Mâle'    => 'male',
        'Male'    => 'male',
        'MALE'    => 'male',
        'm'       => 'male',
        'Femelle' => 'femelle',
        'FEMELLE' => 'femelle',
        'f'       => 'femelle',
    ];

    public function up(): void
    {
        foreach (['cats', 'kittens'] as $table) {
            foreach (self::CORRESPONDANCES as $avant => $apres) {
                DB::table($table)->where('sexe', $avant)->update(['sexe' => $apres]);
            }
        }
    }

    /**
     * On ne remet pas l'accent.
     *
     * Redescendre signifierait rendre a la base l'incoherence qu'elle avait,
     * et on ne saurait meme pas quelle ligne portait quelle forme. La forme
     * sans accent reste lisible par l'ancien code — c'est deja ce que le
     * back-office ecrivait.
     */
    public function down(): void
    {
        //
    }
};
