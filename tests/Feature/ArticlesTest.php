<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La rubrique Articles.
 *
 * Le site en ligne de la chatterie affiche « Aucun article disponible » : une
 * rubrique vide et indexee. Ici, un article n'apparait que s'il est publie ET
 * que sa date est atteinte — les deux conditions comptent.
 */
class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_liste_repond_et_montre_les_articles_publies(): void
    {
        $this->seed();

        $article = Article::publies()->firstOrFail();

        $this->get('/articles')
            ->assertOk()
            ->assertSee($article->titre, escape: false);
    }

    public function test_un_brouillon_n_apparait_ni_en_liste_ni_en_fiche(): void
    {
        $this->seed();

        $brouillon = Article::create([
            'titre' => 'Brouillon de test',
            'chapeau' => 'Ne doit jamais sortir.',
            'corps' => 'Texte.',
            'date_publication' => now()->subDay(),
            'est_publie' => false,
        ]);

        $this->get('/articles')->assertOk()->assertDontSee('Brouillon de test');
        $this->get('/articles/'.$brouillon->slug)->assertNotFound();
    }

    /**
     * Une date a venir programme l'article : la case cochee ne suffit pas.
     * C'est ce qui permet d'ecrire a l'avance sans avoir a penser a revenir.
     */
    public function test_un_article_programme_reste_invisible_jusqu_a_sa_date(): void
    {
        $this->seed();

        $futur = Article::create([
            'titre' => 'Article programmé',
            'chapeau' => 'Pour plus tard.',
            'corps' => 'Texte.',
            'date_publication' => now()->addWeek(),
            'est_publie' => true,
        ]);

        $this->get('/articles')->assertOk()->assertDontSee('Article programmé');
        $this->get('/articles/'.$futur->slug)->assertNotFound();

        $futur->update(['date_publication' => now()->subDay()]);

        $this->get('/articles/'.$futur->slug)->assertOk()->assertSee('Article programmé', escape: false);
    }

    /** Le texte saisi par l'eleveuse ne doit jamais pouvoir injecter de balise. */
    public function test_le_corps_est_echappe_avant_d_etre_balise(): void
    {
        $this->seed();

        $article = Article::create([
            'titre' => 'Échappement',
            'chapeau' => 'Test.',
            'corps' => "## Titre <script>alert(1)</script>\n\nUn **gras** et un <b>b</b>.",
            'date_publication' => now()->subDay(),
            'est_publie' => true,
        ]);

        $html = $this->get('/articles/'.$article->slug)->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('<strong>gras</strong>', $html);
        $this->assertStringContainsString('&lt;b&gt;', $html);
    }

    public function test_les_articles_publies_figurent_au_sitemap(): void
    {
        $this->seed();

        $article = Article::publies()->firstOrFail();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(route('articles.show', $article), escape: false);
    }
}
