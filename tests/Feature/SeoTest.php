<?php

namespace Tests\Feature;

use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sitemap, robots.txt, redirections des anciennes adresses et donnees
 * structurees.
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** Extrait et decode le premier bloc JSON-LD d'une page. */
    private function donneesStructurees(string $chemin): ?array
    {
        $html = $this->get($chemin)->assertOk()->getContent();

        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);

        return json_decode(trim($m[1] ?? ''), true);
    }

    /**
     * Blade compile sa directive de contexte meme au milieu d'un tableau PHP.
     * La cle du meme nom etait remplacee par du code compile et le JSON-LD
     * sortait inexploitable — sur les deux pages, depuis toujours, sans que
     * rien ne le signale. Un moteur ignore purement un bloc sans contexte.
     */
    public function test_l_accueil_expose_une_fiche_d_etablissement_valide(): void
    {
        $j = $this->donneesStructurees('/');

        $this->assertNotNull($j, 'Le JSON-LD de l’accueil doit être décodable.');
        $this->assertSame('https://schema.org', $j['@context'] ?? null);
        $this->assertSame('LocalBusiness', $j['@type'] ?? null);

        $this->assertNotEmpty($j['telephone'] ?? null);
        $this->assertSame('FR', $j['address']['addressCountry'] ?? null);
        $this->assertArrayHasKey('geo', $j);
        $this->assertNotEmpty($j['sameAs'] ?? []);
    }

    public function test_la_page_questions_expose_une_faq_valide(): void
    {
        $j = $this->donneesStructurees('/questions');

        $this->assertNotNull($j);
        $this->assertSame('https://schema.org', $j['@context'] ?? null);
        $this->assertSame('FAQPage', $j['@type'] ?? null);
        $this->assertNotEmpty($j['mainEntity'] ?? []);
        $this->assertSame('Question', $j['mainEntity'][0]['@type'] ?? null);
    }

    public function test_le_sitemap_est_un_xml_valide_et_liste_les_pages(): void
    {
        $xml = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->getContent();

        $doc = simplexml_load_string($xml);
        $this->assertNotFalse($doc, 'Le sitemap doit être un XML valide.');

        // false en second argument : sans lui, iterator_to_array ecrase les
        // entrees, toutes les cles valant « url », et il ne reste que la derniere.
        $adresses = array_map(fn ($u) => (string) $u->loc, iterator_to_array($doc->url, false));

        foreach (['/chatons', '/elevage', '/adopter', '/mentions-legales'] as $attendue) {
            $this->assertTrue(
                (bool) array_filter($adresses, fn ($a) => str_ends_with($a, $attendue)),
                "Le sitemap doit contenir {$attendue}."
            );
        }
    }

    /**
     * Une fiche chaton sans numero ICAD ni numero de portee LOOF renvoie un 404 :
     * l'annoncer dans un sitemap enverrait les moteurs contre un mur.
     */
    public function test_le_sitemap_ignore_les_fiches_non_publiees(): void
    {
        $brouillon = Kitten::first();
        $brouillon->update(['est_publie' => false]);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString('/chatons/'.$brouillon->slug, $xml);
    }

    public function test_le_robots_ecarte_le_back_office_et_annonce_le_sitemap(): void
    {
        $txt = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Disallow: /admin', $txt);
        $this->assertStringContainsString('sitemap.xml', $txt);
    }

    public static function anciennesAdresses(): array
    {
        return [
            'cats'              => ['/cats', '/elevage'],
            'gallery'           => ['/gallery', '/galerie'],
            'infos'             => ['/infos', '/le-maine-coon'],
            'infos/origins'     => ['/infos/origins', '/le-maine-coon'],
            'infos/nutrition'   => ['/infos/nutrition', '/le-maine-coon'],
            'infos/health'      => ['/infos/health', '/le-maine-coon'],
            'infos/preparation' => ['/infos/preparation', '/le-maine-coon'],
            'legal/mentions'    => ['/legal/mentions', '/mentions-legales'],
        ];
    }

    /**
     * Une 301 et non une 302 : elle transmet au moteur la reputation acquise par
     * l'ancienne adresse, et les navigateurs la gardent en memoire.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('anciennesAdresses')]
    public function test_l_ancienne_adresse_redirige_definitivement(string $ancienne, string $attendue): void
    {
        $reponse = $this->get($ancienne);

        $reponse->assertStatus(301);
        $this->assertStringEndsWith($attendue, $reponse->headers->get('Location'));
    }
}
