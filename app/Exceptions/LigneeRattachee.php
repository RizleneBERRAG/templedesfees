<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * On a voulu effacer un chat qui est pere ou mere d'une portee.
 *
 * Les clefs etrangeres des portees sont en nullOnDelete : effacer un
 * reproducteur ne bloque rien, cela vide simplement « pere_id » ou « mere_id ».
 * Et c'est bien le probleme. La suppression passe sans erreur, et la ligne
 * « Parents » se vide toute seule sur la fiche de chacun de ses chatons.
 *
 * Pour un elevage, c'est tout sauf anodin : la lignee est ce qu'une famille
 * regarde pour juger une portee, et le calcul de la fratrie s'appuie dessus —
 * sans pere ni mere, deux chatons de la meme portee cessent d'etre reconnus
 * comme freres et soeurs.
 *
 * Rien ne se perd qu'on ne puisse resaisir, mais personne ne verrait que
 * quelque chose manque. C'est le genre de degat qu'on ne decouvre que des mois
 * plus tard, quand une famille demande pourquoi la fiche ne dit plus rien des
 * parents.
 *
 * Cette exception est la derniere barriere, celle qu'aucun chemin d'ecriture ne
 * contourne. L'espace de gestion, lui, retire le bouton en amont : l'eleveur ne
 * devrait jamais la rencontrer.
 */
class LigneeRattachee extends RuntimeException
{
}
