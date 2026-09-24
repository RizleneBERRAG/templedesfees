<?php

namespace Tests\Feature;

use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Les filtres des listes publiques.
 *
 * Celui du sexe etait casse, et de la pire facon : sans rien afficher
 * d'anormal. La base ecrit « male » avec son accent circonflexe, l'adresse
 * l'ecrit sans. Le controleur comparait donc les deux formes, ne trouvait
 * jamais rien, et deux choses en decoulaient :
 *
 *   — le lien « Males » n'etait pas rendu du tout, faute de compte ;
 *   — l'adresse ?sexe=male renvoyait une page vide, sans message d'erreur.
 *
 * Rien dans la page ne disait qu'un filtre manquait. C'est exactement ce
 * qu'un test doit attraper, parce qu'un relecteur, lui, ne le verra pas.
 */
class FiltresTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_le_filtre_male_rend_les_males_et_non_une_page_vide(): void
    {
        $males = Cat::publies()->get()->filter(fn (Cat $c) => $c->sexeEnAdresse() === 'male');

        $this->assertNotEmpty($males, "L'elevage de demonstration n'a aucun male : le test ne prouverait rien.");

        $reponse = $this->get('/elevage?sexe=male');

        $reponse->assertOk();

        /*
         * assertSee sans second argument echappe l'aiguille, et il le faut :
         * deux etalons portent une apostrophe dans leur nom, que le gabarit
         * rend en &#039;.
         */
        foreach ($males as $chat) {
            $reponse->assertSee($chat->nom);
        }

        /*
         * Et aucune femelle ne passe au travers. On l'observe sur l'etiquette
         * de la carte plutot que sur les noms : un nom peut reapparaitre dans
         * un pedigree ou une donnee structuree sans qu'une carte soit rendue.
         */
        $reponse->assertDontSee('data-sexe="femelle"', false);
    }

    public function test_la_barre_propose_le_filtre_des_males(): void
    {
        $this->get('/elevage')
            ->assertOk()
            ->assertSee('sexe=male', false);
    }

    public function test_le_filtre_par_role_rend_le_bon_nombre_de_chats(): void
    {
        $etalons = Cat::publies()->get()->filter(fn (Cat $c) => $c->role->value === 'etalon');

        $this->assertNotEmpty($etalons);

        $reponse = $this->get('/elevage?role=etalon')->assertOk();

        foreach ($etalons as $chat) {
            $reponse->assertSee($chat->nom, false);
        }
    }

    public function test_un_filtre_inconnu_ne_vide_pas_la_page(): void
    {
        /*
         * Un parametre qui ne correspond a rien est ignore plutot que
         * d'aboutir a une liste vide : une adresse partagee de travers, ou
         * un robot qui bricole la requete, ne doit pas donner une page
         * blanche au visiteur suivant.
         */
        $this->get('/elevage?role=chevalier')
            ->assertOk()
            ->assertSee(Cat::publies()->first()->nom, false);
    }

    /**
     * Le tri se refait aussi dans la page, pour l'apercu statique qui n'a
     * pas de serveur pour l'appliquer. Le contrat est en deux morceaux :
     * la liste declare les parametres qu'elle lit, chaque carte porte sa
     * valeur. Si l'un des deux saute, les filtres redeviennent morts sur
     * l'apercu — sans que rien ne casse ici.
     */
    public function test_les_cartes_portent_de_quoi_trier_sans_serveur(): void
    {
        $this->get('/elevage')
            ->assertOk()
            ->assertSee('data-filtre="role sexe"', false)
            ->assertSee('data-sexe="male"', false)
            ->assertSee('data-role="etalon"', false);

        /*
         * Le seed laisse volontairement les chatons en brouillon, faute de
         * numeros legaux : sans eux la liste n'est pas rendue du tout, et le
         * test ne prouverait rien. On en publie donc un.
         */
        $portee = Litter::publiees()->orderByDesc('date_naissance')->firstOrFail();
        $portee->forceFill(['loof_portee_numero' => 'LO-2026-0001'])->save();

        $chaton = $portee->kittens()->firstOrFail();
        $chaton->forceFill([
            'icad_numero' => '250269812345678',
            'est_publie'  => true,
        ])->save();

        $this->assertTrue($chaton->fresh()->estPubliable());

        $this->get('/chatons')
            ->assertOk()
            ->assertSee('data-filtre="statut"', false)
            ->assertSee('data-statut="'.$chaton->statut->value.'"', false);
    }
}
