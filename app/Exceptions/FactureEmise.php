<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * On a voulu effacer une fiche qui porte une facture.
 *
 * Les cles etrangeres sont en cascade : effacer un chaton efface ses
 * reservations, effacer une portee efface ses chatons. Pour une fiche saisie
 * par erreur, c'est ce qu'on veut. Pour un acompte encaisse, c'est la perte
 * d'un numero de facture, d'une somme recue et d'un contrat — ce qu'aucune
 * comptabilite n'admet.
 *
 * Cette exception est la derniere barriere, celle qu'aucun chemin d'ecriture
 * ne contourne. Le back-office, lui, retire le bouton en amont : l'eleveuse
 * ne devrait jamais la rencontrer.
 */
class FactureEmise extends RuntimeException
{
}
