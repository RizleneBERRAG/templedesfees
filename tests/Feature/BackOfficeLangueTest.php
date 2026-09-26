<?php

namespace Tests\Feature;

use App\Models\Cat;
use App\Models\Litter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le back-office parle francais, et rien d'autre.
 *
 * Filament livre une traduction francaise, mais elle n'est pas complete :
 * soixante-treize de ses clefs n'y figurent pas. Ca n'aurait rien fait si la
 * langue de repli avait ete l'anglais. Elle etait le francais elle-meme — une
 * clef manquante ne retombait donc sur rien, et s'affichait telle quelle.
 *
 * L'eleveur avait de vrais identifiants a l'ecran. A cote de chaque menu
 * deroulant de ses fiches, un bouton s'appelait litteralement
 * « filament-forms::components.select.actions.clear.label ».
 *
 * Deux reponses, et ce fichier tient les deux : les clefs que cette
 * administration affiche sont traduites dans lang/vendor, et le repli est
 * repasse a l'anglais pour que tout le reste soit au moins lisible.
 */
class BackOfficeLangueTest extends TestCase
{
    use RefreshDatabase;

    private function eleveuse(): User
    {
        $this->seed();

        return User::where('email', 'letempledesfees@outlook.fr')->firstOrFail();
    }

    /**
     * La cause racine, tenue par une ligne.
     *
     * Tant que la langue de repli differe de la langue d'affichage, une clef
     * oubliee donne au pire un libelle anglais. Le jour ou les deux se
     * confondent, elle donne son propre nom technique.
     */
    public function test_la_langue_de_repli_n_est_pas_la_langue_d_affichage(): void
    {
        $this->assertNotSame(
            config('app.locale'),
            config('app.fallback_locale'),
            'Avec la meme langue des deux cotes, une traduction manquante '
            ."s'affiche sous sa forme technique au lieu de retomber sur autre chose.",
        );
    }

    public function test_les_libelles_que_filament_n_a_pas_traduits_le_sont_ici(): void
    {
        $attendus = [
            'filament::components/loading-section.label'               => 'Chargement…',
            'filament-forms::components.select.actions.clear.label'    => 'Effacer la sélection',
            'filament-forms::components.select.search_label'           => 'Rechercher',
            'filament-tables::table.loading'                           => 'Chargement…',
            'filament-tables::table.columns.icon.boolean.true'         => 'Oui',
            'filament-tables::table.columns.icon.boolean.false'        => 'Non',
        ];

        foreach ($attendus as $clef => $libelle) {
            $this->assertSame($libelle, __($clef), "La clef {$clef} n'est pas traduite.");
        }
    }

    /**
     * Et le controle qui vaut pour tout le reste : aucun ecran ne doit laisser
     * paraitre une clef. On les cherche par leur forme, « paquet::groupe.clef »,
     * qui n'a aucune raison d'exister dans une page rendue.
     */
    public function ecrans(): array
    {
        $this->seed();

        $chat   = Cat::firstOrFail();
        $portee = Litter::firstOrFail();

        return [
            'tableau de bord'  => '/admin',
            'reproducteurs'    => '/admin/cats',
            'fiche du chat'    => "/admin/cats/{$chat->slug}/edit",
            'portées'          => '/admin/litters',
            'fiche de portée'  => "/admin/litters/{$portee->slug}/edit",
            'chatons'          => '/admin/kittens',
            'photos'           => '/admin/photos',
            'articles'         => '/admin/articles',
            'questions'        => '/admin/faqs',
            'nouvelle question' => '/admin/faqs/create',
            'avis'             => '/admin/reviews',
            'demandes'         => '/admin/adoption-requests',
            'messages'         => '/admin/contact-messages',
            'réservations'     => '/admin/reservations',
            'réglages'         => '/admin/settings',
        ];
    }

    public function test_aucun_ecran_n_affiche_une_clef_de_traduction(): void
    {
        $eleveuse = $this->eleveuse();

        $fautifs = [];

        foreach ($this->ecrans() as $nom => $chemin) {
            $html = $this->actingAs($eleveuse)->get($chemin)->assertOk()->getContent();

            if (preg_match_all('/filament[a-z-]*::[a-z0-9_\/.-]+/i', $html, $trouvees)) {
                $fautifs[$nom] = array_slice(array_unique($trouvees[0]), 0, 4);
            }
        }

        $this->assertSame([], $fautifs,
            "Des clefs de traduction s'affichent telles quelles :\n"
            .json_encode($fautifs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
