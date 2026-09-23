<?php

namespace App\Console\Commands;

use App\Enums\KittenStatus;
use App\Models\Kitten;
use App\Models\Reservation;
use App\Services\Caisse;
use Illuminate\Console\Command;

/**
 * Outil de demonstration.
 *
 * Prepare une reservation prete a montrer : un chaton disponible, une famille
 * fictive, un acompte et un delai. La commande affiche le lien a ouvrir.
 *
 * Elle sert a faire la demonstration du parcours complet devant la cliente
 * avant que le compte de paiement existe. A supprimer le jour ou l'elevage
 * travaille avec ses propres reservations.
 *
 *   php artisan demo:reservation            -> cree la reservation et donne le lien
 *   php artisan demo:reservation --reset    -> efface toutes les reservations
 */
class DemoReservation extends Command
{
    protected $signature = 'demo:reservation
        {--reset : Effacer les réservations et libérer les chatons}';

    protected $description = 'Crée une réservation de démonstration, prête à montrer';

    public function handle(): int
    {
        if ($this->option('reset')) {
            return $this->menage();
        }

        $chaton = Kitten::where('statut', KittenStatus::Disponible)
            ->whereDoesntHave('reservations', fn ($q) => $q->where('statut', 'en_attente'))
            ->first();

        if (! $chaton) {
            $this->warn('Aucun chaton disponible sans réservation en cours.');
            $this->line('  Lancez d’abord : php artisan demo:numeros');

            return self::FAILURE;
        }

        $reservation = Reservation::create([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Camille',
            'nom'              => 'Dupuis',
            'email'            => 'camille.dupuis@example.fr',
            'telephone'        => '06 12 34 56 78',
            'acompte_centimes' => config('chatterie.paiement.acompte_defaut_centimes'),
            'expire_le'        => now()->addDays(config('chatterie.paiement.delai_jours'))->endOfDay(),
            'note_interne'     => 'Réservation de démonstration — à supprimer avant la mise en ligne.',
        ]);

        $this->newLine();
        $this->info('Réservation de démonstration créée.');
        $this->line('  Chaton  : '.$chaton->nom.' ('.$chaton->robe.')');
        $this->line('  Famille : '.$reservation->nomComplet().' · '.$reservation->email);
        $this->line('  Acompte : '.$reservation->acompteFormate()
            .' · à régler avant le '.$reservation->expire_le->translatedFormat('j F Y'));
        $this->newLine();
        $this->line('  '.$reservation->lienPublic());
        $this->newLine();

        if (Caisse::enDemonstration()) {
            $this->comment('  Mode démonstration actif : le bouton marque la réservation payée,');
            $this->comment('  sans carte et sans prélèvement. La page l’annonce en rouge.');
        } elseif (Caisse::estOuverte()) {
            $this->comment('  Stripe est configuré : utilisez la carte de test 4242 4242 4242 4242.');
        } else {
            $this->warn('  Ni Stripe ni le mode démonstration ne sont actifs :');
            $this->warn('  le bouton dira que le paiement en ligne n’est pas encore ouvert.');
            $this->line('  Pour montrer le parcours : PAIEMENT_DEMONSTRATION=true dans .env');
        }

        return self::SUCCESS;
    }

    private function menage(): int
    {
        $n = Reservation::count();

        // On passe par annuler() plutot que par une suppression seche : c'est
        // lui qui rend les chatons a la vente.
        Reservation::with('kitten')->get()->each->annuler('Démonstration terminée.');
        Reservation::query()->delete();

        $this->warn("$n réservation(s) effacée(s), les chatons sont de nouveau proposés.");

        return self::SUCCESS;
    }
}
