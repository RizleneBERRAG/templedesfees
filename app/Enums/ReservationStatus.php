<?php

namespace App\Enums;

/**
 * Le cycle de vie d'une reservation.
 *
 * Une reservation n'est pas une commande : elle nait d'une decision de
 * l'eleveuse, apres la visite, et elle n'engage le chaton qu'une fois
 * l'acompte encaisse. D'ou les deux temps du debut — l'attente puis le
 * paiement — et les trois sorties possibles.
 */
enum ReservationStatus: string
{
    /** Creee par l'eleveuse, le lien est parti, l'acompte n'est pas arrive. */
    case EnAttente = 'en_attente';

    /** L'acompte est encaisse. Le chaton est bloque. */
    case Payee = 'payee';

    /** Le delai est passe sans paiement : le chaton est rendu disponible. */
    case Expiree = 'expiree';

    /** L'eleveuse a repris la main avant paiement, ou apres accord. */
    case Annulee = 'annulee';

    /** L'acompte a ete rendu. Le chaton redevient disponible. */
    case Remboursee = 'remboursee';

    public function libelle(): string
    {
        return match ($this) {
            self::EnAttente  => 'En attente de l’acompte',
            self::Payee      => 'Acompte reçu',
            self::Expiree    => 'Expirée',
            self::Annulee    => 'Annulée',
            self::Remboursee => 'Remboursée',
        };
    }

    /** La couleur de la pastille dans le back-office. */
    public function couleur(): string
    {
        return match ($this) {
            self::EnAttente  => 'warning',
            self::Payee      => 'success',
            self::Expiree    => 'gray',
            self::Annulee    => 'gray',
            self::Remboursee => 'danger',
        };
    }

    /**
     * Ce statut immobilise-t-il le chaton ?
     *
     * Seul l'acompte encaisse bloque. Une reservation en attente ne reserve
     * rien : tant que rien n'est paye, le chaton reste proposable a quelqu'un
     * d'autre, ce qui est exactement ce que dit le site.
     */
    public function bloqueLeChaton(): bool
    {
        return $this === self::Payee;
    }

    /** Peut-on encore payer cette reservation ? */
    public function attendUnPaiement(): bool
    {
        return $this === self::EnAttente;
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->libelle()])->all();
    }
}
