<?php

namespace Tests\Feature;

use App\Filament\Resources\Cats\CatResource;
use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Litters\LitterResource;
use App\Filament\Resources\Photos\PhotoResource;
use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le back-office Filament : acces, et rendu de chaque ecran.
 * Ces tests attrapent une erreur fatale dans un formulaire ou une table —
 * exactement ce qui ne doit pas se decouvrir devant la cliente.
 */
class BackOfficeTest extends TestCase
{
    use RefreshDatabase;

    private function eleveuse(): User
    {
        $this->seed();

        return User::where('email', 'letempledesfees@outlook.fr')->firstOrFail();
    }

    public static function ecrans(): array
    {
        return [
            'tableau de bord'   => ['/admin'],
            'portées'           => ['/admin/litters'],
            'nouvelle portée'   => ['/admin/litters/create'],
            'chatons'           => ['/admin/kittens'],
            'nouveau chaton'    => ['/admin/kittens/create'],
            'reproducteurs'     => ['/admin/cats'],
            'photos'            => ['/admin/photos'],
            'nouvelle photo'    => ['/admin/photos/create'],
            'avis'              => ['/admin/reviews'],
            'demandes'          => ['/admin/adoption-requests'],
            'messages'          => ['/admin/contact-messages'],
            'réglages'          => ['/admin/settings'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('ecrans')]
    public function test_l_ecran_repond(string $chemin): void
    {
        $this->actingAs($this->eleveuse())->get($chemin)->assertOk();
    }

    /**
     * On demande a l'application de fabriquer ses propres liens, exactement comme
     * le fait le bouton « Modifier » d'une liste.
     *
     * Ne jamais ecrire ces URLs a la main ici : une version precedente le faisait
     * et le test passait alors que le back-office renvoyait 404 en vrai. Filament
     * construisait le lien avec la cle de route du modele (le slug) et le
     * resolvait avec une autre cle. Un chemin ecrit a la main teste le chemin,
     * pas le lien sur lequel l'eleveuse clique.
     */
    public function test_les_ecrans_d_edition_repondent(): void
    {
        $eleveuse = $this->eleveuse();

        $liens = [
            'portée'       => LitterResource::getUrl('edit', ['record' => Litter::firstOrFail()]),
            'chaton'       => KittenResource::getUrl('edit', ['record' => Kitten::firstOrFail()]),
            'reproducteur' => CatResource::getUrl('edit', ['record' => Cat::firstOrFail()]),
            'photo'        => PhotoResource::getUrl('edit', ['record' => Photo::firstOrFail()]),
        ];

        foreach ($liens as $quoi => $lien) {
            $this->actingAs($eleveuse)
                ->get($lien)
                ->assertOk("L'ecran d'edition d'une {$quoi} ne repond pas : {$lien}");
        }
    }

    public function test_le_lien_de_retour_vers_le_site_est_present(): void
    {
        $this->actingAs($this->eleveuse())
            ->get('/admin')
            ->assertOk()
            ->assertSee('Retourner sur le site');
    }
    /**
     * Filament pose par defaut un encart affichant sa version et des liens vers
     * sa documentation. Il n'a rien a faire sur le tableau de bord d'une cliente,
     * et une reinstallation du panneau le remettrait sans prevenir.
     */
    public function test_le_tableau_de_bord_ne_fait_pas_la_promotion_de_filament(): void
    {
        $this->actingAs($this->eleveuse())
            ->get('/admin')
            ->assertOk()
            // On vise les liens promotionnels de l'encart, pas le mot « Filament » :
            // celui-ci apparait aussi dans les noms de classes PHP que Livewire
            // serialise dans la page, invisibles pour l'utilisateur.
            ->assertDontSee('filamentphp.com', escape: false)
            ->assertDontSee('github.com/filamentphp', escape: false);
    }
    public static function ecransAvecPhoto(): array
    {
        return [
            'reproducteur' => ['/admin/cats/create'],
            'chaton'       => ['/admin/kittens/create'],
            'portée'       => ['/admin/litters/create'],
            'photo'        => ['/admin/photos/create'],
        ];
    }

    /**
     * Les colonnes photo_principale et chemin stockent un chemin de fichier. Les
     * ecrans generes automatiquement les presentaient en simples champs texte :
     * il aurait fallu taper « images/cats/uzumaki.webp » a la main, donc en
     * pratique on ne pouvait pas ajouter de photo. Chaque ecran doit offrir un
     * vrai champ d'envoi.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('ecransAvecPhoto')]
    public function test_l_ecran_offre_un_vrai_champ_d_envoi(string $chemin): void
    {
        $html = $this->actingAs($this->eleveuse())->get($chemin)->assertOk()->getContent();

        $this->assertStringContainsString('fi-fo-file-upload', $html, "Aucun champ d'envoi sur {$chemin}.");

        $this->assertDoesNotMatchRegularExpression(
            '/<input[^>]*type="text"[^>]*(photo_principale|photo_secondaire)/',
            $html,
            "Une photo ne doit pas se saisir dans un champ texte sur {$chemin}."
        );
    }
    /**
     * Le formulaire de pre-reservation est la conversion principale du site. Sans
     * ecran pour lire les demandes, elles tombaient dans un trou : une demande
     * etait deja en base sans que personne puisse l'ouvrir.
     */
    public function test_une_demande_d_adoption_se_lit_et_se_suit(): void
    {
        $demande = \App\Models\AdoptionRequest::create([
            'prenom' => 'Camille', 'nom' => 'Durand', 'email' => 'camille@example.test',
            'message' => 'Nous cherchons un chaton pour la fin de l’année.',
        ]);

        $this->actingAs($this->eleveuse())
            ->get(\App\Filament\Resources\AdoptionRequests\AdoptionRequestResource::getUrl('edit', ['record' => $demande]))
            ->assertOk()
            ->assertSee('Camille')
            ->assertSee('fin de l’année', escape: false);

        $this->assertSame('nouveau', $demande->fresh()->statut);
    }

    public function test_un_message_de_contact_se_lit(): void
    {
        $message = \App\Models\ContactMessage::create([
            'objet' => 'visite', 'prenom' => 'Sofiane', 'email' => 'sofiane@example.test',
            'message' => 'Serait-il possible de venir un samedi ?',
        ]);

        $this->actingAs($this->eleveuse())
            ->get(\App\Filament\Resources\ContactMessages\ContactMessageResource::getUrl('edit', ['record' => $message]))
            ->assertOk()
            ->assertSee('Sofiane')
            ->assertSee('venir un samedi', escape: false);
    }

    public function test_un_reglage_se_modifie(): void
    {
        // eleveuse() peuple la base : l'appeler avant d'interroger les reglages.
        $eleveuse = $this->eleveuse();
        $reglage = \App\Models\Setting::where('cle', 'legal.siren')->firstOrFail();

        $this->actingAs($eleveuse)
            ->get(\App\Filament\Resources\Settings\SettingResource::getUrl('edit', ['record' => $reglage]))
            ->assertOk()
            ->assertSee('legal.siren');
    }

    /**
     * Rien ne se cree a la main dans ces trois rubriques : les demandes et les
     * messages viennent du site, les reglages du seeder. Un ecran de creation
     * n'inviterait qu'a fabriquer de fausses entrees ou des cles orphelines.
     */
    public function test_ces_rubriques_n_offrent_aucune_creation(): void
    {
        foreach ([
            \App\Filament\Resources\AdoptionRequests\AdoptionRequestResource::class,
            \App\Filament\Resources\ContactMessages\ContactMessageResource::class,
            \App\Filament\Resources\Settings\SettingResource::class,
        ] as $resource) {
            $this->assertFalse($resource::canCreate(), $resource.' ne doit pas permettre la création.');
            $this->assertArrayNotHasKey('create', $resource::getPages());
        }
    }
    public function test_le_back_office_est_ferme_aux_visiteurs(): void
    {
        $this->seed();

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    /**
     * canAccessPanel() s'appuie sur une liste explicite : un compte cree pour
     * autre chose ne doit pas heriter de l'acces au back-office.
     */
    public function test_un_compte_hors_liste_est_refuse(): void
    {
        $this->seed();

        $intrus = User::create([
            'name'     => 'Quelqu’un',
            'email'    => 'quelquun@example.com',
            'password' => 'peu-importe',
        ]);

        $this->actingAs($intrus)->get('/admin')->assertForbidden();
    }
}
