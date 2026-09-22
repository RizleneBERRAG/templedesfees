<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les trois points ou l'audit du site actuel du client se retournait contre le
 * site qui le remplace : les titres, la page d'erreur et les en-tetes.
 */
class QualiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public static function pages(): array
    {
        return [
            'accueil'   => ['/'],
            'chatons'   => ['/chatons'],
            'élevage'   => ['/elevage'],
            'le maine coon' => ['/le-maine-coon'],
            'galerie'   => ['/galerie'],
            'adopter'   => ['/adopter'],
            'questions' => ['/questions'],
            'contact'   => ['/contact'],
            'mentions'  => ['/mentions-legales'],
        ];
    }

    /**
     * Une page sans h1 ne dit pas a Google de quoi elle parle. Huit pages sur
     * neuf en etaient depourvues : x-section-head produisait toujours un h2.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_la_page_a_exactement_un_titre_de_premier_niveau(string $chemin): void
    {
        $html = $this->get($chemin)->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), "La page {$chemin} doit avoir un h1 et un seul.");
    }

    public function test_les_fiches_ont_aussi_leur_titre_de_premier_niveau(): void
    {
        $chat = \App\Models\Cat::publies()->firstOrFail();

        $html = $this->get('/elevage/'.$chat->slug)->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
    }

    public function test_la_page_404_est_celle_du_site_et_non_celle_de_laravel(): void
    {
        $this->get('/une-adresse-qui-nexiste-pas')
            ->assertNotFound()
            ->assertSee('Cette page n’existe pas', escape: false)
            ->assertSee('Retour à l’accueil', escape: false)
            ->assertSee('noindex', escape: false);
    }

    /**
     * Les anciennes adresses exactes sont redirigees en 301 — voir SeoTest. Restent
     * les adresses plus profondes du meme site, qu'aucune redirection exacte ne
     * couvre : la 404 doit alors proposer la bonne rubrique.
     */
    public function test_la_page_404_oriente_depuis_une_ancienne_rubrique(): void
    {
        foreach (['/cats/cmnheyqj70000rqieoked7fbp', '/infos/origins/morphologie'] as $profonde) {
            $this->get($profonde)
                ->assertNotFound()
                ->assertSee('Vous cherchiez sans doute', escape: false);
        }

        // Une adresse inconnue ne doit rien proposer au hasard.
        $this->get('/vraiment-nimporte-quoi')
            ->assertNotFound()
            ->assertDontSee('Vous cherchiez sans doute', escape: false);
    }

    public function test_les_entetes_de_securite_sont_poses(): void
    {
        $reponse = $this->get('/')->assertOk();

        $reponse->assertHeader('X-Content-Type-Options', 'nosniff');
        $reponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $reponse->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertNotNull($reponse->headers->get('Permissions-Policy'));
        $this->assertNull($reponse->headers->get('X-Powered-By'), 'La version de PHP ne doit pas être annoncée.');
    }

    public function test_la_politique_de_contenu_couvre_le_public_sans_brider_le_back_office(): void
    {
        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
        // La carte a besoin de ses tuiles.
        $this->assertStringContainsString('basemaps.cartocdn.com', $csp);

        // Le back-office repose sur Alpine et Livewire : pas de CSP, plutot
        // qu'une CSP si permissive qu'elle ne protegerait de rien.
        $this->assertNull($this->get('/admin/login')->headers->get('Content-Security-Policy'));
    }
}
