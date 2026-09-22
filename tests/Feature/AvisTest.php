<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les avis repris de la fiche Google, sur la page Contact.
 */
class AvisTest extends TestCase
{
    use RefreshDatabase;

    private function avis(array $attributs = []): Review
    {
        return Review::create(array_merge([
            'prenom'     => 'Camille',
            'note'       => 5,
            'texte'      => 'Un accueil remarquable et un chaton parfaitement sociabilisé.',
            'est_publie' => true,
        ], $attributs));
    }

    /**
     * La section reste affichee sans aucun avis : elle porte le formulaire de
     * depot, qui doit rester accessible meme quand il n'y a rien a montrer.
     * Seules les cartes disparaissent.
     */
    public function test_sans_avis_la_section_reste_mais_sans_carte(): void
    {
        $this->seed();

        $this->get('/contact')
            ->assertOk()
            ->assertSee('Ce que disent les familles')
            ->assertSee('Laissez votre avis')
            ->assertDontSee('cell-b');
    }

    public function test_un_avis_depose_arrive_en_attente_et_ne_parait_pas(): void
    {
        $this->seed();

        $this->post('/avis', [
            'prenom' => 'Marion',
            'note'   => 5,
            'texte'  => 'Nous avons adopté Nala il y a un mois, tout s’est très bien passé du début à la fin.',
            'rgpd'   => '1',
        ])->assertRedirect();

        $avis = Review::where('prenom', 'Marion')->firstOrFail();

        $this->assertFalse($avis->est_publie, 'Un avis déposé ne doit jamais paraître sans relecture.');
        $this->assertSame('site', $avis->source);
        $this->assertNotNull($avis->consentement_le, 'Le consentement doit être horodaté.');

        // On vise le TEXTE de l'avis et non le prenom : le message de
        // confirmation dit « Merci Marion », ce qui est voulu et ne constitue
        // pas une publication.
        $this->get('/contact')
            ->assertSee('votre avis est bien arrivé', escape: false)
            ->assertDontSee('adopté Nala', escape: false);
    }

    public function test_l_accord_de_publication_est_obligatoire(): void
    {
        $this->seed();

        $this->post('/avis', [
            'prenom' => 'Sans accord',
            'note'   => 5,
            'texte'  => 'Un texte assez long pour passer la longueur minimale demandée par le formulaire.',
        ])->assertSessionHasErrors('rgpd', null, 'avis');

        $this->assertSame(0, Review::where('prenom', 'Sans accord')->count());
    }

    public function test_le_piege_a_robots_bloque_le_depot(): void
    {
        $this->seed();

        $this->post('/avis', [
            'prenom' => 'Robot',
            'note'   => 5,
            'texte'  => 'Un texte assez long pour passer la longueur minimale demandée par le formulaire.',
            'rgpd'   => '1',
            'site'   => 'https://spam.test',
        ])->assertSessionHasErrors('site', null, 'avis');

        $this->assertSame(0, Review::where('prenom', 'Robot')->count());
    }

    /**
     * Les erreurs du formulaire d'avis ne doivent pas s'afficher sous le
     * formulaire de contact : la page en porte deux, chacun son sac d'erreurs.
     */
    public function test_les_erreurs_ne_debordent_pas_sur_l_autre_formulaire(): void
    {
        $this->seed();

        $reponse = $this->post('/avis', ['prenom' => '', 'note' => 5, 'texte' => 'court']);

        $reponse->assertSessionHasErrors(['prenom', 'texte'], null, 'avis');
        $reponse->assertSessionDoesntHaveErrors(['prenom', 'texte']);
    }

    public function test_un_avis_publie_s_affiche(): void
    {
        $this->seed();
        $this->avis();

        $this->get('/contact')
            ->assertOk()
            ->assertSee('Ce que disent les familles')
            ->assertSee('Camille')
            ->assertSee('parfaitement sociabilisé', escape: false);
    }

    /**
     * Du plus recent au plus ancien, puis par prenom a date egale. Il y avait un
     * champ d'ordre a la main : deux avis pouvaient porter le meme rang, et le
     * classement devenait alors imprevisible.
     */
    public function test_les_avis_se_classent_par_date_puis_par_prenom(): void
    {
        $this->seed();
        $this->avis(['prenom' => 'Ancien', 'publie_le' => '2024-01-10']);
        $this->avis(['prenom' => 'Recent', 'publie_le' => '2026-05-01']);
        $this->avis(['prenom' => 'Bea',    'publie_le' => '2026-05-01']);
        $this->avis(['prenom' => 'Alice',  'publie_le' => '2026-05-01']);

        $ordre = Review::publies()->pluck('prenom')->all();

        $this->assertSame(['Alice', 'Bea', 'Recent', 'Ancien'], $ordre);
    }

    public function test_un_avis_masque_ne_s_affiche_pas(): void
    {
        $this->seed();
        $this->avis(['prenom' => 'Sabine', 'est_publie' => false]);

        $this->get('/contact')->assertOk()->assertDontSee('Sabine');
    }

    /**
     * La page Mentions legales s'engage a ne publier les temoignages que sous le
     * prenom seul. La table n'a donc pas de colonne pour un nom de famille : la
     * regle est tenue par le schema, pas par la vigilance de qui saisit.
     */
    public function test_aucun_nom_de_famille_ne_peut_etre_stocke(): void
    {
        $this->seed();

        $colonnes = array_keys($this->avis()->getAttributes());

        foreach (['nom', 'nom_famille', 'last_name'] as $interdite) {
            $this->assertNotContains($interdite, $colonnes);
        }
    }

    public function test_le_lien_vers_la_fiche_google_apparait_s_il_est_renseigne(): void
    {
        $this->seed();
        $this->avis();

        $this->get('/contact')->assertDontSee('Voir tous les avis sur Google');

        // Par le modele, pas en masse : c'est l'evenement d'enregistrement qui
        // vide le cache des reglages.
        Setting::where('cle', 'contact.avis_google')->firstOrFail()
            ->update(['valeur' => 'https://exemple.test/avis']);

        $this->get('/contact')
            ->assertSee('Voir tous les avis sur Google')
            ->assertSee('https://exemple.test/avis', escape: false);
    }
}
