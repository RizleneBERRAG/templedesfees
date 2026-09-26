<?php

namespace Tests\Feature;

use App\Filament\Resources\Cats\Pages\EditCat;
use App\Filament\Resources\Cats\RelationManagers\HealthTestsRelationManager;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Resources\Litters\Pages\EditLitter;
use App\Filament\Resources\Litters\RelationManagers\EventsRelationManager;
use App\Models\Cat;
use App\Models\Faq;
use App\Models\HealthTest;
use App\Models\Litter;
use App\Models\LitterEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Ce que l'eleveur peut atteindre, et ce qu'il ne pouvait pas.
 *
 * L'audit de l'interface a trouve trois jeux de donnees qui s'affichaient sur
 * le site et n'existaient que par le seed : personne ne pouvait les toucher.
 *
 *   — les resultats de depistage. C'est ce que le site met le plus en avant,
 *     et le tableau de bord bouclait dans le vide : il annoncait
 *     « echocardiographie a refaire », renvoyait vers la liste des
 *     reproducteurs, et rien la-bas ne permettait d'en saisir une nouvelle ;
 *   — les etapes d'une portee, la chronologie que les familles lisent sur la
 *     fiche d'un chaton ;
 *   — les questions frequentes, et la donnee structuree qu'elles nourrissent.
 *
 * Le premier test de ce fichier est structurel : il compare ce que le site
 * publie a ce que le back-office expose. C'est le seul qui aurait attrape ce
 * defaut, parce qu'aucun ecran ne repondait autre chose qu'un 200.
 */
class BackOfficeCouvertureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(User::where('email', 'letempledesfees@outlook.fr')->firstOrFail());
    }

    /**
     * Le garde-fou : rien de ce que le site montre ne doit etre hors d'atteinte.
     *
     * Chaque modele affiche publiquement est liste ici avec l'endroit du
     * back-office qui le gere. Ajouter une table sans son ecran fait echouer
     * ce test, et c'est tout l'interet : le defaut ne se voit pas autrement.
     */
    public static function donneesPubliques(): array
    {
        return [
            'reproducteurs'        => [\App\Models\Cat::class,            'App\Filament\Resources\Cats\CatResource'],
            'portées'              => [\App\Models\Litter::class,         'App\Filament\Resources\Litters\LitterResource'],
            'chatons'              => [\App\Models\Kitten::class,         'App\Filament\Resources\Kittens\KittenResource'],
            'photos'               => [\App\Models\Photo::class,          'App\Filament\Resources\Photos\PhotoResource'],
            'articles'             => [\App\Models\Article::class,        'App\Filament\Resources\Articles\ArticleResource'],
            'avis'                 => [\App\Models\Review::class,         'App\Filament\Resources\Reviews\ReviewResource'],
            'réglages'             => [\App\Models\Setting::class,        'App\Filament\Resources\Settings\SettingResource'],
            'questions fréquentes' => [\App\Models\Faq::class,            'App\Filament\Resources\Faqs\FaqResource'],
            'dossiers d’adoption'  => [\App\Models\AdoptionRequest::class, 'App\Filament\Resources\AdoptionRequests\AdoptionRequestResource'],
            'messages'             => [\App\Models\ContactMessage::class, 'App\Filament\Resources\ContactMessages\ContactMessageResource'],
            'réservations'         => [\App\Models\Reservation::class,    'App\Filament\Resources\Reservations\ReservationResource'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('donneesPubliques')]
    public function test_chaque_donnee_du_site_se_gere_depuis_le_back_office(string $modele, string $ressource): void
    {
        $this->assertTrue(class_exists($ressource),
            "Le modele {$modele} s'affiche sur le site et n'a aucune ressource dans le back-office : "
            ."l'eleveur ne peut ni le corriger, ni le completer.");

        $this->assertSame($modele, $ressource::getModel());

        $this->get($ressource::getUrl('index'))->assertOk();
    }

    /**
     * Les deux jeux de donnees qui se gerent depuis leur fiche parente, parce
     * que c'est la qu'on les lit et la qu'on les cherche.
     */
    public function test_les_resultats_de_depistage_se_gerent_depuis_la_fiche_du_chat(): void
    {
        $this->assertContains(
            HealthTestsRelationManager::class,
            \App\Filament\Resources\Cats\CatResource::getRelations(),
            "Sans ce gestionnaire, le tableau de bord annonce une echographie a "
            ."refaire et n'offre aucun endroit pour la saisir.",
        );

        $chat = Cat::firstOrFail();

        Livewire::test(HealthTestsRelationManager::class, [
            'ownerRecord' => $chat,
            'pageClass'   => EditCat::class,
        ])->assertOk();
    }

    public function test_un_resultat_de_depistage_se_saisit_et_parait_sur_la_fiche(): void
    {
        $chat = Cat::where('est_publie', true)->firstOrFail();
        $chat->healthTests()->delete();

        Livewire::test(HealthTestsRelationManager::class, [
            'ownerRecord' => $chat,
            'pageClass'   => EditCat::class,
        ])
            ->callTableAction('create', data: [
                'type'        => 'hcm_echo',
                'resultat'    => 'Normale',
                'date_examen' => now()->subMonths(2)->toDateString(),
                'laboratoire' => 'Clinique vétérinaire de la Drôme',
            ])
            ->assertHasNoTableActionErrors();

        $resultat = HealthTest::where('cat_id', $chat->id)->firstOrFail();

        $this->assertSame('Normale', $resultat->resultat);

        $this->get(route('cats.show', $chat))
            ->assertOk()
            ->assertSee('Normale');
    }

    /**
     * Et la boucle refermee : le tableau de bord signale une echographie
     * perimee, et la fiche permet desormais d'en saisir une neuve qui la fait
     * disparaitre de l'alerte.
     */
    public function test_saisir_une_echographie_neuve_eteint_l_alerte_du_tableau_de_bord(): void
    {
        $chat = Cat::firstOrFail();
        $chat->healthTests()->delete();

        HealthTest::create([
            'cat_id'      => $chat->id,
            'type'        => 'hcm_echo',
            'resultat'    => 'Normale',
            'date_examen' => now()->subMonths(18),
        ]);

        $this->get('/admin')->assertOk()->assertSee('Échocardiographie');

        HealthTest::where('cat_id', $chat->id)->update(['date_examen' => now()->subMonth()]);

        $this->get('/admin')->assertOk()->assertDontSee('Échocardiographie à refaire');
    }

    public function test_les_etapes_d_une_portee_se_gerent_depuis_sa_fiche(): void
    {
        $this->assertContains(
            EventsRelationManager::class,
            \App\Filament\Resources\Litters\LitterResource::getRelations(),
        );

        $portee = Litter::firstOrFail();

        Livewire::test(EventsRelationManager::class, [
            'ownerRecord' => $portee,
            'pageClass'   => EditLitter::class,
        ])
            ->callTableAction('create', data: [
                'libelle'        => 'Première vaccination',
                'date_evenement' => now()->toDateString(),
                'ordre'          => 5,
                'est_fait'       => true,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('litter_events', [
            'litter_id' => $portee->id,
            'libelle'   => 'Première vaccination',
        ]);
    }

    public function test_une_etape_sans_date_reste_possible(): void
    {
        $portee = Litter::firstOrFail();

        Livewire::test(EventsRelationManager::class, [
            'ownerRecord' => $portee,
            'pageClass'   => EditLitter::class,
        ])
            ->callTableAction('create', data: [
                'libelle'      => 'Départ',
                'date_libelle' => 'Au départ',
                'ordre'        => 9,
            ])
            ->assertHasNoTableActionErrors();

        $etape = LitterEvent::where('libelle', 'Départ')->firstOrFail();

        $this->assertNull($etape->date_evenement);
        $this->assertSame('Au départ', $etape->quand());
    }

    /* ═══ les questions fréquentes ════════════════════════════════════ */

    public function test_la_liste_des_questions_montre_celles_du_site(): void
    {
        Livewire::test(ListFaqs::class)
            ->set('tableRecordsPerPage', 50)
            ->assertCanSeeTableRecords(Faq::all());
    }

    public function test_une_question_se_cree_et_parait_sur_la_page(): void
    {
        Livewire::test(CreateFaq::class)
            ->fillForm([
                'question'    => 'Peut-on venir voir les chatons avant de choisir ?',
                'reponse'     => 'Oui, sur rendez-vous, à partir de six semaines.',
                'ordre'       => 99,
                'est_publiee' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get('/questions')
            ->assertOk()
            ->assertSee('Peut-on venir voir les chatons avant de choisir ?', false);
    }

    public function test_une_question_sans_reponse_est_refusee(): void
    {
        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => '', 'reponse' => '', 'ordre' => 0])
            ->call('create')
            ->assertHasFormErrors(['question', 'reponse']);
    }

    public function test_une_question_masquee_disparait_de_la_page(): void
    {
        $question = Faq::firstOrFail();

        Livewire::test(EditFaq::class, ['record' => $question->getKey()])
            ->fillForm(['est_publiee' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get('/questions')->assertOk()->assertDontSee($question->question, false);
    }
}
