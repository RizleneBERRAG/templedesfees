<?php

namespace Tests\Feature;

use App\Enums\KittenStatus;
use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les donnees structurees de la fiche d'un chaton.
 *
 * Elles permettent a Google d'afficher le prix et la disponibilite directement
 * dans ses resultats, au lieu d'un simple lien. C'est ce qui separe une ligne
 * de texte d'une annonce qui montre « 1 600 € · En stock ».
 *
 * La regle qui compte ici : une offre n'est declaree QUE si le prix existe. Un
 * bloc « offers » vide, ou a zero, est signale en erreur par Google et peut
 * faire retirer la fiche de ses resultats enrichis. Mieux vaut un produit sans
 * offre qu'une offre vide.
 */
class BalisageChatonTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed>|null le bloc Product de la page */
    private function produit(Kitten $chaton): ?array
    {
        $corps = $this->get('/chatons/'.$chaton->slug)->assertOk()->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $corps, $blocs);

        foreach ($blocs[1] as $brut) {
            $donnees = json_decode(trim($brut), true);

            if (($donnees['@type'] ?? null) === 'Product') {
                return $donnees;
            }
        }

        return null;
    }

    private function chaton(): Kitten
    {
        $this->seed();

        return Kitten::publies()->firstOrFail();
    }

    public function test_la_fiche_declare_un_produit(): void
    {
        $chaton = $this->chaton();
        $produit = $this->produit($chaton);

        $this->assertNotNull($produit, 'Aucun bloc Product sur la fiche.');
        $this->assertSame($chaton->nom, $produit['name']);
        $this->assertNotEmpty($produit['description']);
        $this->assertNotEmpty($produit['image']);
    }

    public function test_le_prix_et_la_disponibilite_sont_declares(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['prix_centimes' => 160000, 'statut' => KittenStatus::Disponible]);

        $offre = $this->produit($chaton->fresh())['offers'] ?? null;

        $this->assertNotNull($offre, 'Un chaton avec un prix doit porter une offre.');
        $this->assertSame('1600.00', $offre['price']);
        $this->assertSame('EUR', $offre['priceCurrency']);
        $this->assertSame('https://schema.org/InStock', $offre['availability']);
    }

    /** Un chaton reserve n'est plus a prendre, et le balisage doit le dire. */
    public function test_un_chaton_reserve_n_est_plus_annonce_disponible(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['prix_centimes' => 160000, 'statut' => KittenStatus::Reserve]);

        $offre = $this->produit($chaton->fresh())['offers'];

        $this->assertSame('https://schema.org/OutOfStock', $offre['availability']);
    }

    /**
     * Sans prix, aucune offre : c'est la regle qui protege la fiche.
     */
    public function test_sans_prix_aucune_offre_n_est_declaree(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['prix_centimes' => null]);

        $produit = $this->produit($chaton->fresh());

        $this->assertNotNull($produit, 'Le produit reste déclaré, même sans prix.');
        $this->assertArrayNotHasKey('offers', $produit,
            'Une offre sans prix est signalée en erreur par Google.');
    }
}
