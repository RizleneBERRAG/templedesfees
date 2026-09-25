<?php

namespace Tests\Feature;

use App\Enums\KittenStatus;
use App\Filament\Resources\Kittens\Pages\EditKitten;
use App\Filament\Resources\Litters\Pages\EditLitter;
use App\Models\Kitten;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Ce qu'une suppression emporte avec elle.
 *
 * Les cles etrangeres sont posees en cascade : effacer un chaton efface ses
 * reservations, effacer une portee efface ses chatons — donc leurs
 * reservations aussi. C'est le bon comportement pour une fiche qu'on vient de
 * saisir par erreur. C'en est un tres mauvais pour une reservation encaissee :
 * elle porte un numero de facture, une somme recue et un contrat, et une
 * facture emise ne disparait pas parce qu'on a range une fiche.
 *
 * Le back-office offre un bouton « Supprimer » sur la fiche chaton comme sur
 * la portee. Rien n'y prevenait l'eleveuse, et rien ne l'arretait.
 */
class BackOfficeSuppressionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(User::where('email', 'letempledesfees@outlook.fr')->firstOrFail());
    }

    private function chatonPaye(): Kitten
    {
        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();

        $reservation = Reservation::create([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Camille',
            'nom'              => 'Dupuis',
            'email'            => 'camille@example.test',
            'acompte_centimes' => 30000,
            'expire_le'        => now()->addDays(7),
        ]);

        $reservation->payer();

        $this->assertNotNull($reservation->fresh()->facture_numero,
            "Le decor du test est faux : la reservation n'a pas de facture.");

        return $chaton->fresh();
    }

    public function test_le_bouton_supprimer_disparait_quand_l_acompte_est_encaisse(): void
    {
        $chaton = $this->chatonPaye();

        Livewire::test(EditKitten::class, ['record' => $chaton->getRouteKey()])
            ->assertActionHidden('delete')
            ->assertActionVisible('suppression-impossible');
    }

    /**
     * Et la barriere du dessous, celle qu'aucun chemin ne contourne : action
     * groupee, commande artisan, console. Retirer le bouton ne suffit pas —
     * c'est le modele qui doit refuser.
     */
    public function test_le_modele_refuse_d_effacer_un_chaton_encaisse(): void
    {
        $chaton = $this->chatonPaye();

        $this->expectException(\App\Exceptions\FactureEmise::class);

        try {
            $chaton->delete();
        } finally {
            $this->assertDatabaseHas('kittens', ['id' => $chaton->id]);
            $this->assertSame(1, Reservation::where('kitten_id', $chaton->id)->count(),
                'La reservation encaissee a ete effacee avec le chaton : son numero '
                .'de facture et le montant recu sont perdus.');
        }
    }

    public function test_la_portee_se_protege_de_la_meme_facon(): void
    {
        $chaton = $this->chatonPaye();
        $portee = $chaton->litter;

        Livewire::test(EditLitter::class, ['record' => $portee->getRouteKey()])
            ->assertActionHidden('delete')
            ->assertActionVisible('suppression-impossible');

        $this->expectException(\App\Exceptions\FactureEmise::class);

        try {
            $portee->delete();
        } finally {
            $this->assertDatabaseHas('litters', ['id' => $portee->id]);
            $this->assertDatabaseHas('kittens', ['id' => $chaton->id]);
            $this->assertSame(1, Reservation::where('kitten_id', $chaton->id)->count());
        }
    }

    /**
     * L'inverse doit rester possible, sinon la protection devient une prison :
     * une fiche saisie par erreur, qui n'a jamais rien encaisse, s'efface.
     */
    public function test_un_chaton_sans_reservation_encaissee_se_supprime_normalement(): void
    {
        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();

        Livewire::test(EditKitten::class, ['record' => $chaton->getRouteKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('kittens', ['id' => $chaton->id]);
    }

    /**
     * Une reservation en attente n'a rien encaisse et ne porte pas de facture :
     * elle ne protege donc pas le chaton, et part avec lui.
     */
    public function test_une_reservation_en_attente_n_empeche_pas_la_suppression(): void
    {
        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();

        Reservation::create([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Noé',
            'nom'              => 'Martin',
            'email'            => 'noe@example.test',
            'acompte_centimes' => 30000,
            'expire_le'        => now()->addDays(7),
        ]);

        Livewire::test(EditKitten::class, ['record' => $chaton->getRouteKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('kittens', ['id' => $chaton->id]);
    }
    /**
     * L'action groupee de la liste ne passe pas par le bouton de la fiche :
     * c'est le chemin par lequel une selection un peu large emporterait tout.
     */
    public function test_la_suppression_groupee_epargne_les_chatons_encaisses(): void
    {
        $protege = $this->chatonPaye();
        $libre   = Kitten::whereKeyNot($protege->getKey())
            ->where('statut', KittenStatus::Disponible)->firstOrFail();

        try {
            Livewire::test(\App\Filament\Resources\Kittens\Pages\ListKittens::class)
                ->callTableBulkAction('delete', [$protege, $libre]);
        } catch (\App\Exceptions\FactureEmise) {
            // Le modele a refuse : c'est le resultat attendu, pas un echec.
        }

        $this->assertDatabaseHas('kittens', ['id' => $protege->id]);
        $this->assertSame(1, Reservation::where('kitten_id', $protege->id)->count(),
            "Une selection groupee a emporte une facture emise.");
    }
}
