<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Console\Command;

/**
 * Le menage des reservations.
 *
 * Une reservation en attente dont le delai est passe doit rendre le chaton :
 * sans cela, une famille qui ne donne plus signe de vie immobilise un chaton
 * indefiniment, et le site continue d'afficher « reserve » ce que personne
 * n'a paye.
 *
 * La commande est sans effet sur une reservation payee : seul un
 * remboursement ou une annulation explicite libere un acompte encaisse.
 */
class ReservationsPerimees extends Command
{
    protected $signature = 'reservations:menage';

    protected $description = 'Libère les chatons dont la réservation a expiré sans paiement';

    public function handle(): int
    {
        $perimees = Reservation::where('statut', ReservationStatus::EnAttente)
            ->whereNotNull('expire_le')
            ->where('expire_le', '<', now())
            ->with('kitten')
            ->get();

        if ($perimees->isEmpty()) {
            $this->info('Aucune réservation périmée.');

            return self::SUCCESS;
        }

        foreach ($perimees as $reservation) {
            $reservation->expirer();

            $this->line(sprintf(
                '  %s — %s : délai passé le %s, le chaton est de nouveau proposé.',
                $reservation->kitten?->nom ?? 'chaton supprimé',
                $reservation->nomComplet(),
                $reservation->expire_le->translatedFormat('j F Y')
            ));
        }

        $this->info($perimees->count().' réservation(s) libérée(s).');

        return self::SUCCESS;
    }
}
