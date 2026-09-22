<?php

namespace Tests\Unit;

use App\Models\Litter;
use Tests\TestCase;

/**
 * La phrase de disponibilite bascule toute seule avec le calendrier. Sans test,
 * la formulation redevient fausse un matin sans que personne ne s'en aperçoive —
 * et c'est precisement le reproche fait au site actuel du client.
 */
class PhraseDisponibiliteTest extends TestCase
{
    public function test_une_date_a_venir_s_annonce_a_partir_du(): void
    {
        $portee = new Litter(['date_disponibilite' => now()->addWeeks(2)]);

        $this->assertStringContainsString('à partir du', $portee->phraseDisponibilite());
        $this->assertStringNotContainsString('depuis', $portee->phraseDisponibilite());
    }

    public function test_une_date_passee_s_annonce_depuis_le(): void
    {
        $portee = new Litter(['date_disponibilite' => now()->subWeeks(2)]);

        $this->assertStringContainsString('depuis le', $portee->phraseDisponibilite());
        $this->assertStringNotContainsString('à partir', $portee->phraseDisponibilite());
    }

    public function test_sans_date_il_n_y_a_pas_de_phrase(): void
    {
        $this->assertNull((new Litter)->phraseDisponibilite());
    }
}
