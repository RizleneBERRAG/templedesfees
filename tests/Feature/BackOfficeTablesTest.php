<?php

namespace Tests\Feature;

use App\Filament\Resources\AdoptionRequests\Pages\ListAdoptionRequests;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Resources\Cats\Pages\ListCats;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\Kittens\Pages\ListKittens;
use App\Filament\Resources\Litters\Pages\ListLitters;
use App\Filament\Resources\Photos\Pages\ListPhotos;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Models\Article;
use App\Models\Cat;
use App\Models\ContactMessage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Photo;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Les listes du back-office : ce qu'elles montrent, et ce que leurs filtres
 * retiennent.
 *
 * Un filtre de back-office qui ne filtre pas est le meme defaut que celui
 * qu'on vient de corriger cote public, et il se voit encore moins : l'eleveuse
 * croira simplement qu'aucune fiche ne correspond. On les exerce donc tous, un
 * par un, en verifiant a la fois ce qui doit paraitre et ce qui ne doit pas.
 */
class BackOfficeTablesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(User::where('email', 'letempledesfees@outlook.fr')->firstOrFail());
    }

    /* ═══ les listes s'affichent, et avec leurs lignes ════════════════ */

    /**
     * Chaque liste montre bien ses lignes.
     *
     * Le nombre par page est releve a cinquante : les listes paginent a dix, et
     * sans cela le test echouerait sur le onzieme reproducteur pour une raison
     * qui n'a rien a voir avec ce qu'il veut prouver.
     */
    public static function listes(): array
    {
        return [
            'reproducteurs' => [ListCats::class, Cat::class],
            'portées'       => [ListLitters::class, Litter::class],
            'chatons'       => [ListKittens::class, Kitten::class],
            'articles'      => [ListArticles::class, Article::class],
            'photos'        => [ListPhotos::class, Photo::class],
            'réglages'      => [ListSettings::class, Setting::class],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('listes')]
    public function test_la_liste_montre_bien_ses_enregistrements(string $page, string $modele): void
    {
        $lignes = $modele::all();

        $this->assertNotEmpty($lignes, 'Le seed ne fournit rien : le test ne prouverait rien.');

        Livewire::test($page)
            ->set('tableRecordsPerPage', 50)
            ->assertCanSeeTableRecords($lignes);
    }

    /**
     * Une liste vide ne doit pas etre une liste cassee. Les trois rubriques qui
     * peuvent legitimement n'avoir aucune ligne au depart sont verifiees a part.
     */
    public function test_les_listes_vides_s_affichent_sans_erreur(): void
    {
        Livewire::test(ListReviews::class)->assertOk();
        Livewire::test(ListContactMessages::class)->assertOk();
        Livewire::test(ListAdoptionRequests::class)->assertOk();
        Livewire::test(ListReservations::class)->assertOk();
    }

    /* ═══ les filtres ═════════════════════════════════════════════════ */

    public function test_le_filtre_par_role_des_reproducteurs_retient_le_bon_monde(): void
    {
        $etalons = Cat::where('role', 'etalon')->get();
        $autres  = Cat::where('role', '<>', 'etalon')->get();

        $this->assertNotEmpty($etalons);
        $this->assertNotEmpty($autres);

        Livewire::test(ListCats::class)
            ->filterTable('role', 'etalon')
            ->assertCanSeeTableRecords($etalons)
            ->assertCanNotSeeTableRecords($autres);
    }

    public function test_le_filtre_publie_des_reproducteurs_fonctionne_dans_les_deux_sens(): void
    {
        $chat = Cat::firstOrFail();
        $chat->forceFill(['est_publie' => false])->save();

        Livewire::test(ListCats::class)
            ->filterTable('est_publie', false)
            ->assertCanSeeTableRecords(Cat::where('est_publie', false)->get())
            ->assertCanNotSeeTableRecords(Cat::where('est_publie', true)->get());

        Livewire::test(ListCats::class)
            ->filterTable('est_publie', true)
            ->assertCanNotSeeTableRecords([$chat]);
    }

    public function test_le_filtre_par_statut_des_chatons_retient_le_bon_monde(): void
    {
        $disponibles = Kitten::where('statut', 'disponible')->get();
        $reserves    = Kitten::where('statut', 'reserve')->get();

        $this->assertNotEmpty($disponibles);

        Livewire::test(ListKittens::class)
            ->filterTable('statut', 'disponible')
            ->assertCanSeeTableRecords($disponibles)
            ->assertCanNotSeeTableRecords($reserves);
    }

    /**
     * Le filtre le plus utile du back-office : « qu'est-ce qu'il me reste a
     * completer ? ». Il doit s'accorder avec la regle legale, pas avec le
     * drapeau de publication.
     */
    public function test_le_filtre_des_mentions_obligatoires_dit_la_verite(): void
    {
        $portee = Litter::firstOrFail();
        $portee->forceFill(['loof_portee_numero' => 'LO-2026-0001'])->save();

        $complet = $portee->kittens()->firstOrFail();
        $complet->forceFill(['icad_numero' => '250269812345678'])->save();

        $incomplets = Kitten::whereKeyNot($complet->getKey())->get();

        Livewire::test(ListKittens::class)
            ->filterTable('mentions', true)
            ->assertCanSeeTableRecords([$complet->fresh()])
            ->assertCanNotSeeTableRecords($incomplets);

        Livewire::test(ListKittens::class)
            ->filterTable('mentions', false)
            ->assertCanNotSeeTableRecords([$complet->fresh()]);
    }

    public function test_le_filtre_par_rubrique_des_articles_retient_le_bon_monde(): void
    {
        $article = Article::firstOrFail();
        $article->forceFill(['categorie' => 'Conseils'])->save();

        $autres = Article::where('categorie', '<>', 'Conseils')->get();

        Livewire::test(ListArticles::class)
            ->filterTable('categorie', 'Conseils')
            ->assertCanSeeTableRecords([$article->fresh()])
            ->assertCanNotSeeTableRecords($autres);
    }

    public function test_le_filtre_des_messages_traites_fonctionne(): void
    {
        $traite = ContactMessage::create([
            'objet' => 'Merci', 'prenom' => 'Anne', 'email' => 'anne@example.test',
            'message' => 'Merci pour tout.', 'est_traite' => true,
        ]);
        $enAttente = ContactMessage::create([
            'objet' => 'Question', 'prenom' => 'Bruno', 'email' => 'bruno@example.test',
            'message' => 'Avez-vous une portée prévue ?', 'est_traite' => false,
        ]);

        Livewire::test(ListContactMessages::class)
            ->filterTable('est_traite', false)
            ->assertCanSeeTableRecords([$enAttente])
            ->assertCanNotSeeTableRecords([$traite]);
    }

    public function test_le_filtre_des_avis_publies_fonctionne(): void
    {
        $publie = Review::create([
            'prenom' => 'Claire', 'note' => 5, 'texte' => 'Chaton superbe et très câlin.',
            'publie_le' => now()->toDateString(), 'est_publie' => true,
        ]);
        $enAttente = Review::create([
            'prenom' => 'Denis', 'note' => 4, 'texte' => 'Accueil chaleureux, suivi sérieux.',
            'publie_le' => now()->toDateString(), 'est_publie' => false,
        ]);

        Livewire::test(ListReviews::class)
            ->filterTable('est_publie', false)
            ->assertCanSeeTableRecords([$enAttente])
            ->assertCanNotSeeTableRecords([$publie]);
    }

    public function test_le_filtre_par_groupe_des_reglages_fonctionne(): void
    {
        $groupe = Setting::firstOrFail()->groupe;

        $dedans = Setting::where('groupe', $groupe)->get();
        $dehors = Setting::where('groupe', '<>', $groupe)->get();

        $this->assertNotEmpty($dehors, 'Il faut au moins deux groupes pour que le test prouve quelque chose.');

        Livewire::test(ListSettings::class)
            ->filterTable('groupe', $groupe)
            ->assertCanSeeTableRecords($dedans)
            ->assertCanNotSeeTableRecords($dehors);
    }

    /* ═══ la recherche ════════════════════════════════════════════════ */

    public function test_la_recherche_trouve_un_reproducteur_par_son_nom(): void
    {
        $chat   = Cat::firstOrFail();
        $autres = Cat::whereKeyNot($chat->getKey())->get();

        Livewire::test(ListCats::class)
            ->searchTable($chat->nom)
            ->assertCanSeeTableRecords([$chat])
            ->assertCanNotSeeTableRecords($autres);
    }

    /* ═══ la suppression groupee ══════════════════════════════════════ */

    public function test_la_suppression_groupee_retire_bien_les_lignes(): void
    {
        $avis = Review::create([
            'prenom' => 'Éric', 'note' => 3, 'texte' => 'Un avis à supprimer pour le test.',
            'publie_le' => now()->toDateString(), 'est_publie' => false,
        ]);

        Livewire::test(ListReviews::class)
            ->callTableBulkAction('delete', [$avis]);

        $this->assertDatabaseMissing('reviews', ['id' => $avis->getKey()]);
    }
}
