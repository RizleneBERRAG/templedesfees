<?php

namespace Tests\Feature;

use App\Enums\CatRole;
use App\Exceptions\LigneeRattachee;
use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * On n'efface pas un chat rattache a une portee.
 *
 * Les clefs etrangeres des portees sont en nullOnDelete : rien n'empechait la
 * suppression, elle vidait simplement « pere_id » et « mere_id ». Sans erreur,
 * sans avertissement, et la ligne « Parents » disparaissait de la fiche de
 * chaque chaton concerne.
 *
 * Pour un elevage c'est un degat serieux : la lignee est ce qu'une famille
 * regarde, et la fratrie se calcule a partir des deux parents — sans eux, deux
 * chatons de la meme portee cessent d'etre reconnus comme freres et soeurs.
 */
class SuppressionChatTest extends TestCase
{
    use RefreshDatabase;

    private function chat(string $nom, string $sexe): Cat
    {
        return Cat::create([
            'nom'  => $nom,
            'slug' => Str::slug($nom),
            'sexe' => $sexe,
            'robe' => 'Red',
            'role' => $sexe === 'male' ? CatRole::Etalon : CatRole::Reproductrice,
        ]);
    }

    private function portee(Cat $pere, Cat $mere): Litter
    {
        return Litter::create([
            'code'           => 'Portée Z — Essai',
            'slug'           => 'portee-z-essai',
            'pere_id'        => $pere->id,
            'mere_id'        => $mere->id,
            'date_naissance' => now()->subWeeks(8),
            'nb_chatons'     => 1,
            'est_publiee'    => true,
        ]);
    }

    public function test_un_chat_sans_portee_s_efface_normalement(): void
    {
        $chat = $this->chat('Sans Portee', 'male');

        $this->assertTrue($chat->peutEtreSupprime());

        $chat->delete();

        $this->assertModelMissing($chat);
    }

    public function test_un_pere_de_portee_ne_s_efface_pas(): void
    {
        $pere = $this->chat('Le Pere', 'male');
        $mere = $this->chat('La Mere', 'femelle');
        $this->portee($pere, $mere);

        $this->assertFalse($pere->fresh()->peutEtreSupprime());

        $this->expectException(LigneeRattachee::class);

        $pere->delete();
    }

    public function test_une_mere_de_portee_ne_s_efface_pas_non_plus(): void
    {
        $pere = $this->chat('Le Pere', 'male');
        $mere = $this->chat('La Mere', 'femelle');
        $this->portee($pere, $mere);

        $this->expectException(LigneeRattachee::class);

        $mere->delete();
    }

    /** Le message nomme la portée : sinon l'éleveur cherche laquelle. */
    public function test_le_message_nomme_la_portee_concernee(): void
    {
        $pere = $this->chat('Le Pere', 'male');
        $mere = $this->chat('La Mere', 'femelle');
        $portee = $this->portee($pere, $mere);

        try {
            $pere->delete();
            $this->fail('La suppression aurait dû être refusée.');
        } catch (LigneeRattachee $e) {
            $this->assertStringContainsString($portee->code, $e->getMessage());
            $this->assertStringContainsString('Le Pere', $e->getMessage());
        }
    }

    /**
     * Le coeur du probleme : la fiche du chaton perdait ses parents en silence.
     */
    public function test_la_fiche_du_chaton_garde_ses_parents(): void
    {
        $pere = $this->chat('Le Pere', 'male');
        $mere = $this->chat('La Mere', 'femelle');
        $portee = $this->portee($pere, $mere);

        Kitten::create([
            'litter_id'  => $portee->id,
            'nom'        => 'Le Chaton',
            'slug'       => 'le-chaton',
            'sexe'       => 'male',
            'robe'       => 'Red',
            'statut'     => 'disponible',
            'est_publie' => true,
        ]);

        try {
            $pere->delete();
        } catch (LigneeRattachee) {
            // Attendu.
        }

        $this->assertSame($pere->id, $portee->fresh()->pere_id,
            'La portée a perdu son père malgré le garde-fou.');
    }
}
