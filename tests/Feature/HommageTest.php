<?php

namespace Tests\Feature;

use App\Models\Cat;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * En memoire d'Olimpia.
 *
 * Une page qui rend hommage a un animal disparu, avec le texte de l'eleveur a
 * la premiere personne. Ce qui est verifie ici tient a ce qu'elle ne doit
 * jamais faire : afficher un bloc vide parce qu'un reglage n'est pas rempli,
 * et surtout annoncer une filiation que personne n'a renseignee.
 */
class HommageTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_repond_et_porte_son_nom(): void
    {
        $this->seed();

        $this->get(route('hommage'))
            ->assertOk()
            ->assertSee('Olimpia')
            ->assertSee('Olimpia Maryliss&nbsp;Country', escape: false)
            ->assertSee('Je ne l’oublierai jamais.')
            // Le mot qui la faisait accourir : c'est le detail de tout le texte.
            ->assertSee('le poulette');
    }

    /** Le texte est sa parole : il vit en reglage, pas dans une vue. */
    public function test_le_texte_se_modifie_depuis_les_reglages(): void
    {
        $this->seed();

        // Par le modele et non par le constructeur de requetes : c'est
        // l'enregistrement du modele qui vide le cache des reglages, et c'est
        // ce que fait le back-office.
        Setting::where('cle', 'hommage.texte')->firstOrFail()
            ->update(['valeur' => 'Elle nous manque.']);

        $this->get(route('hommage'))
            ->assertOk()
            ->assertSee('Elle nous manque.');
    }

    /**
     * Tant que l'eleveur n'a pas dit laquelle de ses chattes est sa fille, le
     * bloc n'existe pas. On ne devine pas une filiation, et on n'affiche pas
     * un encadre vide pour faire joli.
     */
    public function test_la_filiation_ne_s_affiche_que_si_elle_est_renseignee(): void
    {
        $this->seed();

        $this->get(route('hommage'))->assertDontSee('Elle continue');

        $fille = Cat::firstOrFail();
        Setting::where('cle', 'hommage.fille')->firstOrFail()
            ->update(['valeur' => $fille->slug]);

        $this->get(route('hommage'))
            ->assertOk()
            ->assertSee('Elle continue')
            ->assertSee($fille->nom);
    }

    /** Elle a une entree depuis la page des chats, et une seule. */
    public function test_la_page_des_chats_y_mene(): void
    {
        $this->seed();

        $this->get(route('cats.index'))
            ->assertOk()
            ->assertSee(route('hommage'), escape: false)
            ->assertSee('En mémoire');
    }
}
