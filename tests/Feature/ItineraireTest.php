<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les boutons d'itineraire de la page Contact : Google Maps et Waze.
 */
class ItineraireTest extends TestCase
{
    use RefreshDatabase;

    public static function fournisseurs(): array
    {
        return [
            'Google Maps' => ['contact.itineraire_google', 'google.com/maps/dir'],
            'Waze'        => ['contact.itineraire_waze',   'waze.com/ul'],
        ];
    }

    /**
     * La page annonce que l'adresse exacte est communiquee au rendez-vous. Un
     * itineraire porte-a-porte la publierait : les liens visent la commune.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fournisseurs')]
    public function test_le_lien_vise_la_commune_et_non_une_adresse(string $cle, string $domaine): void
    {
        $this->seed();

        $commune = Setting::get('elevage.ville');

        $this->get('/contact')
            ->assertOk()
            ->assertSee($domaine, escape: false)
            ->assertSee(urlencode($commune), escape: false);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fournisseurs')]
    public function test_le_lien_est_remplacable_depuis_les_reglages(string $cle, string $domaine): void
    {
        $this->seed();

        Setting::where('cle', $cle)->firstOrFail()
            ->update(['valeur' => 'https://exemple.test/remplace']);

        $this->get('/contact')
            ->assertOk()
            ->assertSee('https://exemple.test/remplace', escape: false)
            ->assertDontSee($domaine, escape: false);
    }

    /** Rien ne doit etre charge depuis Google ni Waze sur la page elle-meme. */
    public function test_la_page_ne_charge_rien_depuis_ces_services(): void
    {
        $this->seed();

        $html = $this->get('/contact')->assertOk()->getContent();

        foreach (['maps.googleapis.com', 'maps.google.com/maps?', 'embed.waze.com', '<iframe'] as $interdit) {
            $this->assertStringNotContainsString($interdit, $html);
        }
    }

    /** Les deux boutons s'ouvrent dans un nouvel onglet, sans fuite de referrer. */
    public function test_les_liens_s_ouvrent_dans_un_nouvel_onglet(): void
    {
        $this->seed();

        $html = $this->get('/contact')->assertOk()->getContent();

        preg_match_all('/<a[^>]*href="https:\/\/www\.(google\.com\/maps|waze\.com)[^"]*"[^>]*>/', $html, $liens);

        $this->assertCount(2, $liens[0], 'Les deux boutons d’itinéraire doivent être présents.');

        foreach ($liens[0] as $lien) {
            $this->assertStringContainsString('target="_blank"', $lien);
            $this->assertStringContainsString('noopener', $lien);
        }
    }
}
