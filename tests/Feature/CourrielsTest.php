<?php

namespace Tests\Feature;

use App\Enums\KittenStatus;
use App\Mail\Lettre;
use App\Models\AdoptionRequest;
use App\Models\ContactMessage;
use App\Models\Kitten;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Les courriels du site.
 *
 * Deux choses sont verifiees ici, et la seconde compte plus que la premiere :
 * que les lettres partent aux bonnes adresses, et surtout qu'une panne
 * d'envoi ne fasse jamais perdre ce qu'une famille vient d'ecrire.
 */
class CourrielsTest extends TestCase
{
    use RefreshDatabase;

    private const ELEVAGE = 'letempledesfees@outlook.fr';

    /** La lettre partie vers cette vue, ou null. */
    private function lettre(string $vue): ?Lettre
    {
        $trouvee = null;

        Mail::assertSent(Lettre::class, function (Lettre $lettre) use ($vue, &$trouvee) {
            if ($lettre->vue === $vue) {
                $trouvee = $lettre;
            }

            return true;
        });

        return $trouvee;
    }

    private function demandeValide(array $champs = []): array
    {
        return array_merge([
            'prenom' => 'Marion',
            'nom' => 'Delaunay',
            'email' => 'marion@example.test',
            'telephone' => '06 12 34 56 78',
            'logement' => 'Maison avec jardin',
            'message' => 'Nous cherchons un chaton pour la fin de l’année.',
            'rgpd' => '1',
        ], $champs);
    }

    public function test_une_demande_d_adoption_ecrit_a_l_elevage_et_a_la_famille(): void
    {
        $this->seed();
        Mail::fake();

        $this->post('/adopter', $this->demandeValide())->assertRedirect();

        Mail::assertSent(Lettre::class, 2);

        $aLElevage = $this->lettre('elevage.demande-adoption');
        $this->assertNotNull($aLElevage, 'L’élevage doit être prévenu.');
        $this->assertTrue($aLElevage->hasTo(self::ELEVAGE));

        // « Répondre » doit écrire à la famille, pas à l'élevage.
        $this->assertSame('marion@example.test', $aLElevage->repondreA);

        $aLaFamille = $this->lettre('famille.demande-adoption');
        $this->assertNotNull($aLaFamille, 'La famille doit recevoir un accusé de réception.');
        $this->assertTrue($aLaFamille->hasTo('marion@example.test'));
        $this->assertFalse($aLaFamille->hasTo(self::ELEVAGE));
    }

    public function test_un_message_de_contact_ecrit_a_l_elevage_et_a_la_famille(): void
    {
        $this->seed();
        Mail::fake();

        $this->post('/contact', [
            'objet' => array_key_first(ContactMessage::OBJETS_PROPOSES),
            'prenom' => 'Thomas',
            'email' => 'thomas@example.test',
            'message' => 'Bonjour, une question sur les portées à venir.',
            'rgpd' => '1',
        ])->assertRedirect();

        Mail::assertSent(Lettre::class, 2);
        $this->assertNotNull($this->lettre('elevage.message-contact'));
        $this->assertNotNull($this->lettre('famille.message-contact'));
    }

    /**
     * Un avis ne declenche QU'UN courriel, vers l'elevage.
     *
     * Ecrire au visiteur « votre avis est enregistre » juste avant de ne pas
     * le publier serait une promesse qu'on ne tient pas.
     */
    public function test_un_avis_ne_previent_que_l_elevage(): void
    {
        $this->seed();
        Mail::fake();

        $this->post('/avis', [
            'prenom' => 'Camille',
            'email' => 'camille@example.test',
            'note' => 5,
            'texte' => 'Un accueil remarquable et un chaton parfaitement sociabilisé.',
            'rgpd' => '1',
        ])->assertRedirect();

        Mail::assertSent(Lettre::class, 1);
        $this->assertNotNull($this->lettre('elevage.avis-en-attente'));
    }

    /**
     * Le coeur du dispositif : le serveur de courriel est en panne, et la
     * demande arrive quand meme en base, avec sa page de remerciement.
     */
    public function test_une_panne_d_envoi_ne_fait_pas_perdre_la_demande(): void
    {
        $this->seed();

        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP injoignable'));

        $this->post('/adopter', $this->demandeValide(['prenom' => 'Lucie']))
            ->assertRedirect()
            ->assertSessionHas('succes');

        $this->assertDatabaseHas('adoption_requests', ['prenom' => 'Lucie']);
    }

    public function test_une_panne_d_envoi_ne_fait_pas_perdre_un_avis_ni_un_message(): void
    {
        $this->seed();

        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP injoignable'));

        $this->post('/avis', [
            'prenom' => 'Sarah',
            'note' => 4,
            'texte' => 'Des nouvelles régulières et un chaton en pleine forme à l’arrivée.',
            'rgpd' => '1',
        ])->assertRedirect();

        $this->post('/contact', [
            'objet' => array_key_first(ContactMessage::OBJETS_PROPOSES),
            'prenom' => 'Yanis',
            'email' => 'yanis@example.test',
            'message' => 'Bonjour, je souhaite des informations.',
            'rgpd' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('reviews', ['prenom' => 'Sarah']);
        $this->assertDatabaseHas('contact_messages', ['prenom' => 'Yanis']);
    }

    /**
     * Toutes les lettres doivent s'afficher, pas seulement partir.
     *
     * Ce test en a laisse passer une en production : il ne rendait que les
     * deux lettres d'adoption, et les deux lettres de contact nommaient leur
     * variable $message. Or Laravel injecte d'office un $message dans toute
     * vue de courriel — le sien, l'enveloppe — qui ecrasait le notre. La
     * lettre partait bien a l'envoi, mais echouait au rendu, et le facteur
     * avalait l'erreur sans que personne la voie.
     *
     * D'ou la regle que ce test fait respecter : on rend CHAQUE lettre.
     */
    public function test_les_sept_lettres_s_affichent_sans_erreur(): void
    {
        $this->seed();
        Mail::fake();

        $this->post('/adopter', $this->demandeValide())->assertRedirect();

        $this->post('/contact', [
            'objet'   => array_key_first(ContactMessage::OBJETS_PROPOSES),
            'prenom'  => 'Thomas',
            'nom'     => 'Reynaud',
            'email'   => 'thomas@example.test',
            'message' => 'Bonjour, une question sur les portees a venir.',
            'rgpd'    => '1',
        ])->assertRedirect();

        $this->post('/avis', [
            'prenom' => 'Camille',
            'email'  => 'camille@example.test',
            'note'   => 5,
            'texte'  => 'Un accueil remarquable et un chaton parfaitement sociabilise.',
            'rgpd'   => '1',
        ])->assertRedirect();

        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();
        Reservation::create([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Lea',
            'nom'              => 'Marchand',
            'email'            => 'lea@example.test',
            'acompte_centimes' => 30000,
            'expire_le'        => now()->addDays(7),
        ])->payer();

        $attendues = [
            'elevage.demande-adoption', 'famille.demande-adoption',
            'elevage.message-contact',  'famille.message-contact',
            'elevage.avis-en-attente',
            'elevage.acompte-recu',     'famille.acompte-recu',
        ];

        foreach ($attendues as $vue) {
            $lettre = $this->lettre($vue);
            $this->assertNotNull($lettre, "La lettre $vue n'est jamais partie.");

            // C'est render() qui revele une variable absente ou ecrasee.
            $corps = $lettre->render();

            $this->assertNotSame('', trim(strip_tags($corps)), "La lettre $vue s'affiche vide.");
            $this->assertStringNotContainsString('Undefined', $corps);
        }

        // Le contenu, et pas seulement l'absence d'erreur.
        $this->assertStringContainsString('Thomas', $this->lettre('famille.message-contact')->render());
        $this->assertStringContainsString('Marion', $this->lettre('famille.demande-adoption')->render());
    }

    /**
     * L'acompte encaisse : un recu a la famille, un avis a l'elevage.
     *
     * Les deux lettres partent de payer(), et non du bouton de l'espace de
     * gestion : un acompte arrive soit par virement enregistre a la main, soit
     * par Stripe, et la famille recoit la meme chose dans les deux cas.
     */
    public function test_un_acompte_encaisse_ecrit_a_la_famille_et_a_l_elevage(): void
    {
        $this->seed();
        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();

        $reservation = Reservation::create([
            'kitten_id' => $chaton->id,
            'prenom' => 'Camille',
            'nom' => 'Dupuis',
            'email' => 'camille@example.test',
            'acompte_centimes' => 30000,
            'expire_le' => now()->addDays(7),
        ]);

        Mail::fake();
        $reservation->payer();

        Mail::assertSent(Lettre::class, 2);

        $aLaFamille = $this->lettre('famille.acompte-recu');
        $this->assertNotNull($aLaFamille);
        $this->assertTrue($aLaFamille->hasTo('camille@example.test'));
        $this->assertStringContainsString($reservation->fresh()->reference(), $aLaFamille->render());

        $this->assertTrue($this->lettre('elevage.acompte-recu')->hasTo(self::ELEVAGE));
    }

    /** Stripe rejoue ses notifications : le recu ne part qu'une fois. */
    public function test_un_acompte_rejoue_n_envoie_pas_un_second_recu(): void
    {
        $this->seed();
        $chaton = Kitten::where('statut', KittenStatus::Disponible)->firstOrFail();

        $reservation = Reservation::create([
            'kitten_id' => $chaton->id,
            'prenom' => 'Camille',
            'nom' => 'Dupuis',
            'email' => 'camille@example.test',
            'acompte_centimes' => 30000,
            'expire_le' => now()->addDays(7),
        ]);

        Mail::fake();
        $reservation->payer();
        $reservation->payer();
        $reservation->payer();

        Mail::assertSent(Lettre::class, 2);
    }
}
