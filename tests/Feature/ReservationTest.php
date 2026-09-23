<?php

namespace Tests\Feature;

use App\Enums\KittenStatus;
use App\Enums\ReservationStatus;
use App\Models\Kitten;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les reservations et l'acompte.
 *
 * C'est la seule partie du site qui touche a l'argent et qui retire un chaton
 * de la vente. Chaque regle y est verifiee separement, y compris celles qui
 * paraissent evidentes : « payer deux fois ne paie pas deux fois » est
 * exactement le genre de chose qu'un webhook rejoue casse en silence.
 */
class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function chatonDisponible(): Kitten
    {
        $this->seed();

        return Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();
    }

    private function reservationPour(Kitten $chaton, array $attributs = []): Reservation
    {
        return Reservation::create(array_merge([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Camille',
            'nom'              => 'Dupuis',
            'email'            => 'camille@example.test',
            'acompte_centimes' => 30000,
            'expire_le'        => now()->addDays(7),
        ], $attributs));
    }

    /* ── la regle centrale ───────────────────────────────────────── */

    public function test_une_reservation_en_attente_ne_bloque_pas_le_chaton(): void
    {
        $chaton = $this->chatonDisponible();
        $this->reservationPour($chaton);

        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut,
            'Tant que rien n’est payé, le chaton doit rester proposable.');
    }

    public function test_l_acompte_paye_bloque_le_chaton(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $reservation->payer('pi_essai');

        $this->assertSame(ReservationStatus::Payee, $reservation->fresh()->statut);
        $this->assertSame(KittenStatus::Reserve, $chaton->fresh()->statut);
        $this->assertNotNull($reservation->fresh()->paye_le);
    }

    /**
     * Stripe rejoue ses notifications jusqu'a recevoir un accuse de reception.
     * Le second passage ne doit rien changer — ni la date, ni le statut.
     */
    public function test_payer_deux_fois_n_a_pas_d_effet_supplementaire(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $reservation->payer('pi_essai');
        $premierPaiement = $reservation->fresh()->paye_le;

        $this->travel(2)->minutes();
        $reservation->fresh()->payer('pi_autre');

        $apres = $reservation->fresh();

        $this->assertEquals($premierPaiement, $apres->paye_le, 'La date de paiement ne doit pas bouger.');
        $this->assertSame('pi_essai', $apres->stripe_payment_intent);
    }

    /* ── les sorties ─────────────────────────────────────────────── */

    public function test_l_expiration_rend_le_chaton(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton, ['expire_le' => now()->subDay()]);

        $reservation->expirer();

        $this->assertSame(ReservationStatus::Expiree, $reservation->fresh()->statut);
        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut);
    }

    public function test_le_remboursement_remet_le_chaton_en_vente(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $reservation->payer();
        $this->assertSame(KittenStatus::Reserve, $chaton->fresh()->statut);

        $reservation->rembourser('Changement de situation');

        $this->assertSame(ReservationStatus::Remboursee, $reservation->fresh()->statut);
        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut);
    }

    /**
     * Un chaton deja parti ne revient pas en vente parce qu'on annule une
     * vieille reservation.
     */
    public function test_un_chaton_adopte_n_est_jamais_remis_en_vente(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $reservation->payer();
        $chaton->forceFill(['statut' => KittenStatus::Adopte])->save();

        $reservation->rembourser();

        $this->assertSame(KittenStatus::Adopte, $chaton->fresh()->statut);
    }

    /* ── le menage ───────────────────────────────────────────────── */

    public function test_le_menage_libere_les_perimees_et_epargne_les_payees(): void
    {
        $chaton = $this->chatonDisponible();
        $autre = Kitten::where('statut', KittenStatus::Disponible)
            ->where('id', '!=', $chaton->id)->firstOrFail();

        $perimee = $this->reservationPour($chaton, ['expire_le' => now()->subDay()]);
        $payee = $this->reservationPour($autre, ['expire_le' => now()->subDay()]);
        $payee->payer();

        $this->artisan('reservations:menage')->assertSuccessful();

        $this->assertSame(ReservationStatus::Expiree, $perimee->fresh()->statut);
        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut);

        $this->assertSame(ReservationStatus::Payee, $payee->fresh()->statut,
            'Un acompte encaissé ne se libère pas tout seul.');
        $this->assertSame(KittenStatus::Reserve, $autre->fresh()->statut);
    }

    /* ── la page publique ────────────────────────────────────────── */

    public function test_la_page_de_reservation_repond_et_ne_s_indexe_pas(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $this->get($reservation->lienPublic())
            ->assertOk()
            ->assertSee($chaton->nom)
            ->assertSee('300 €')
            ->assertSee('noindex', escape: false);
    }

    public function test_un_jeton_inconnu_renvoie_une_page_introuvable(): void
    {
        $this->seed();

        $this->get(route('reservation.montrer', ['jeton' => str_repeat('x', 40)]))
            ->assertNotFound();
    }

    public function test_on_ne_peut_pas_payer_une_reservation_perimee(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton, ['expire_le' => now()->subDay()]);

        $this->assertFalse($reservation->peutEtrePayee());

        $this->post(route('reservation.payer', ['jeton' => $reservation->jeton]), ['conditions' => '1'])
            ->assertRedirect();

        $this->assertSame(ReservationStatus::EnAttente, $reservation->fresh()->statut);
        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut);
    }

    public function test_le_paiement_exige_l_acceptation_des_conditions(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);

        $this->post(route('reservation.payer', ['jeton' => $reservation->jeton]), [])
            ->assertSessionHasErrors('conditions');

        $this->assertSame(KittenStatus::Disponible, $chaton->fresh()->statut);
    }

    /* ── la notification de paiement ─────────────────────────────── */

    /**
     * Sans signature valable, n'importe qui connaissant l'adresse du webhook
     * pourrait declarer un acompte paye et bloquer un chaton.
     */
    public function test_une_notification_sans_signature_valable_est_refusee(): void
    {
        $this->seed();
        config(['chatterie.paiement.stripe.webhook' => 'whsec_essai']);

        $this->postJson(route('paiement.notification'), ['type' => 'checkout.session.completed'])
            ->assertStatus(400);
    }

    /* ── le back-office ──────────────────────────────────────────── */

    public function test_les_ecrans_de_reservation_repondent(): void
    {
        $chaton = $this->chatonDisponible();
        $reservation = $this->reservationPour($chaton);
        $eleveuse = User::where('email', 'letempledesfees@outlook.fr')->firstOrFail();

        foreach (['/admin/reservations', '/admin/reservations/create'] as $ecran) {
            $this->actingAs($eleveuse)->get($ecran)->assertOk("L'écran $ecran ne répond pas.");
        }

        $this->actingAs($eleveuse)
            ->get(\App\Filament\Resources\Reservations\ReservationResource::getUrl('edit', ['record' => $reservation]))
            ->assertOk();
    }
}
