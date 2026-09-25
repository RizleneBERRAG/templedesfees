<?php

namespace Tests\Feature;

use App\Filament\Resources\AdoptionRequests\AdoptionRequestResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Qui entre dans le back-office, et ce qu'on y a le droit de faire.
 *
 * Trois rubriques ne se creent pas a la main, et une ne se supprime pas. Ce
 * ne sont pas des details d'ergonomie : un reglage efface est un morceau de
 * site qui disparait, et une demande d'adoption inventee depuis le
 * back-office est une trace sans consentement.
 */
class BackOfficeDroitsTest extends TestCase
{
    use RefreshDatabase;

    private function eleveuse(): User
    {
        $this->seed();

        return User::where('email', 'letempledesfees@outlook.fr')->firstOrFail();
    }

    /* ═══ la porte ════════════════════════════════════════════════════ */

    public static function ecrans(): array
    {
        return [
            'tableau de bord' => ['/admin'],
            'portées'         => ['/admin/litters'],
            'chatons'         => ['/admin/kittens'],
            'reproducteurs'   => ['/admin/cats'],
            'photos'          => ['/admin/photos'],
            'articles'        => ['/admin/articles'],
            'avis'            => ['/admin/reviews'],
            'demandes'        => ['/admin/adoption-requests'],
            'messages'        => ['/admin/contact-messages'],
            'réservations'    => ['/admin/reservations'],
            'réglages'        => ['/admin/settings'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('ecrans')]
    public function test_aucun_ecran_ne_s_ouvre_a_un_visiteur(string $chemin): void
    {
        $this->seed();

        $this->get($chemin)->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('ecrans')]
    public function test_chaque_ecran_repond_a_l_eleveuse(string $chemin): void
    {
        $this->actingAs($this->eleveuse())->get($chemin)->assertOk();
    }

    /**
     * Un compte existe peut-etre dans la base sans avoir rien a faire ici : la
     * liste des adresses autorisees est la seule chose qui ouvre la porte.
     */
    public function test_un_compte_hors_liste_reste_dehors(): void
    {
        $this->seed();

        $intrus = User::create([
            'name'     => 'Quelqu’un',
            'email'    => 'quelquun@example.test',
            'password' => bcrypt('mot-de-passe-quelconque'),
        ]);

        $this->assertFalse($intrus->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));
    }

    /* ═══ ce qui ne se cree pas ═══════════════════════════════════════ */

    public function test_un_reglage_ne_se_cree_ni_ne_se_supprime(): void
    {
        $this->actingAs($this->eleveuse());

        $this->assertFalse(SettingResource::canCreate(),
            'Un reglage se modifie, il ne s’invente pas : la liste des clefs est celle que le site lit.');

        $this->assertFalse(SettingResource::canDelete(Setting::firstOrFail()),
            'Un reglage efface, c’est un morceau de site qui disparait sans prevenir.');

        $this->assertFalse(SettingResource::canDeleteAny());

        $this->get('/admin/settings/create')->assertNotFound();
    }

    public function test_un_message_de_contact_ne_se_cree_pas_a_la_main(): void
    {
        $this->actingAs($this->eleveuse());

        $this->assertFalse(ContactMessageResource::canCreate());

        $this->get('/admin/contact-messages/create')->assertNotFound();
    }

    public function test_une_demande_d_adoption_ne_se_cree_pas_a_la_main(): void
    {
        $this->actingAs($this->eleveuse());

        $this->assertFalse(AdoptionRequestResource::canCreate(),
            'Une demande vient d’une famille, avec son consentement daté. '
            .'En inventer une depuis le back-office serait une trace sans origine.');

        $this->get('/admin/adoption-requests/create')->assertNotFound();
    }

    /**
     * Et la liste des reglages ne doit offrir aucune action de suppression,
     * groupee comprise : c'est par la qu'on efface vingt clefs d'un coup.
     */
    public function test_la_liste_des_reglages_n_offre_aucune_suppression(): void
    {
        $this->actingAs($this->eleveuse());

        Livewire::test(ListSettings::class)
            ->assertTableBulkActionDoesNotExist('delete')
            ->assertTableActionDoesNotExist('delete');
    }
}
