<?php

namespace Tests\Feature;

use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le prix sur la fiche publique d'un chaton.
 *
 * Il n'y figurait pas : la refonte avait suppose que l'elevage n'affichait pas
 * ses tarifs. L'eleveur a demande l'inverse le 6 octobre 2026, et il a raison
 * sur le fond — le prix fait partie des mentions d'une offre de cession.
 *
 * Tant qu'il n'est pas saisi, la fiche porte un trait, comme l'ICAD et le
 * numero de portee. Ce qui avertit la famille, c'est le bandeau
 * « Identification en cours » ; ce qui avertit l'eleveur, c'est son tableau de
 * bord. Pas une mention orange sur chaque ligne de la fiche.
 */
class PrixChatonTest extends TestCase
{
    use RefreshDatabase;

    private function chatonPublie(): Kitten
    {
        $this->seed();

        return Kitten::publies()->firstOrFail();
    }

    public function test_le_prix_saisi_parait_sur_la_fiche(): void
    {
        $chaton = $this->chatonPublie();
        $chaton->update(['prix_centimes' => 150000]);

        $this->get('/chatons/'.$chaton->slug)
            ->assertOk()
            ->assertSee('Prix', escape: false)
            // 1 500 €, avec l'espace insecable que pose Monnaie::euros().
            ->assertSee($chaton->fresh()->prixFormate(), escape: false);
    }

    /** Un prix avec des centimes ne doit pas s'arrondir en silence. */
    public function test_un_prix_avec_des_centimes_s_affiche_en_entier(): void
    {
        $chaton = $this->chatonPublie();
        $chaton->update(['prix_centimes' => 149550]);

        $this->get('/chatons/'.$chaton->slug)
            ->assertOk()
            ->assertSee($chaton->fresh()->prixFormate(), escape: false);
    }

    public function test_sans_prix_la_fiche_porte_un_trait(): void
    {
        $chaton = $this->chatonPublie();
        $chaton->update(['prix_centimes' => null]);

        $reponse = $this->get('/chatons/'.$chaton->slug)->assertOk();

        // La ligne reste : un prix absent n'efface pas le champ, il le laisse vide.
        $reponse->assertSee('<th>Prix</th>', escape: false);
        $reponse->assertSee('—', escape: false);
        $reponse->assertDontSee('À compléter', escape: false);
    }
}
