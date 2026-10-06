<?php

namespace Tests\Feature;

use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La balise description des fiches chatons.
 *
 * Les dix chatons mis en ligne le 5 octobre 2026 n'avaient aucune description
 * saisie : leurs dix pages sont parties avec une balise vide, et un partage sur
 * les reseaux sans une ligne de texte. Google compose alors lui-meme le resume
 * affiche dans ses resultats, avec des bouts de la page pris au hasard.
 *
 * La fiche compose donc un resume a partir de ce qu'elle sait deja, et ne
 * retient une phrase que si elle tient en entier : un resume coupe au milieu
 * d'un nom de lieu dessert la page qu'il devait servir.
 */
class ResumeMoteursTest extends TestCase
{
    use RefreshDatabase;

    private const LIMITE = 155;

    private function chaton(): Kitten
    {
        $this->seed();

        return Kitten::publies()->with('litter.pere', 'litter.mere')->firstOrFail();
    }

    public function test_la_description_ecrite_par_l_eleveur_prime(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['description' => 'Un chaton calme, qui dort sur les genoux dès qu’on s’assoit.']);

        $this->assertStringContainsString(
            'dort sur les genoux',
            $chaton->fresh()->resumePourLesMoteurs(),
        );
    }

    public function test_sans_description_le_resume_se_compose_tout_seul(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['description' => null]);

        $resume = $chaton->fresh()->resumePourLesMoteurs();

        $this->assertNotSame('', $resume, 'Une fiche ne part jamais sans résumé.');
        $this->assertStringContainsString($chaton->nom, $resume);
        $this->assertStringContainsString('Maine Coon', $resume);
    }

    /** Une phrase coupee au milieu dessert la page qu'elle devait servir. */
    public function test_le_resume_ne_coupe_jamais_une_phrase(): void
    {
        $this->seed();

        foreach (Kitten::publies()->with('litter.pere', 'litter.mere')->get() as $chaton) {
            $chaton->update(['description' => null]);
            $resume = $chaton->fresh()->resumePourLesMoteurs();

            $this->assertLessThanOrEqual(self::LIMITE, mb_strlen($resume),
                "Le résumé de {$chaton->nom} dépasse la limite.");

            $this->assertStringEndsWith('.', $resume,
                "Le résumé de {$chaton->nom} ne finit pas sur une phrase complète.");

            $this->assertStringNotContainsString('...', $resume);
            $this->assertStringNotContainsString('…', $resume);
        }
    }

    /** Et la page elle-meme doit la porter, pas seulement le modele. */
    public function test_la_page_porte_une_description_non_vide(): void
    {
        $chaton = $this->chaton();
        $chaton->update(['description' => null]);

        $corps = $this->get('/chatons/'.$chaton->slug)->assertOk()->getContent();

        preg_match('/<meta name="description" content="(.*?)"/s', $corps, $trouve);

        $this->assertNotEmpty($trouve, 'La balise description est absente.');
        $this->assertNotSame('', trim($trouve[1]), 'La balise description est vide.');
    }
}
