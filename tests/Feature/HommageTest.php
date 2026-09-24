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

    /**
     * La page est un parcours : un moment par ecran, dans un ordre qui compte.
     * On aime, on sourit du mot, on est fier, on perd, et on decouvre qu'il
     * reste quelqu'un. Un moment qui passerait devant un autre casserait la
     * seule chose que cette page ait a faire.
     */
    public function test_les_moments_se_suivent_dans_l_ordre(): void
    {
        $this->seed();

        $html = $this->get(route('hommage'))->assertOk()->getContent();

        $ordre = [
            'moment-mot',       // le mot, seul
            'moment-elle',      // ce qu'elle etait
            'moment-depart',    // la perte
            'moment-adieu',     // la phrase signee
            'moment-planche',   // ses tirages
        ];

        $positions = array_map(fn ($classe) => strpos($html, $classe), $ordre);

        $this->assertNotContains(false, $positions, 'Un moment manque à la page.');

        $triees = $positions;
        sort($triees);

        $this->assertSame($triees, $positions, 'Les moments ne se suivent plus dans le bon ordre.');
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

        $this->get(route('hommage'))->assertDontSee('Sa fille, ici même');

        $fille = Cat::firstOrFail();
        Setting::where('cle', 'hommage.fille')->firstOrFail()
            ->update(['valeur' => $fille->slug]);

        $this->get(route('hommage'))
            ->assertOk()
            ->assertSee('Sa fille, ici même')
            ->assertSee($fille->nom);
    }

    /* ── le seuil ────────────────────────────────────────────────── */

    /**
     * Le voile qui se pose par-dessus le site a l'arrivee. Le souvenir de sa
     * fermeture vit dans le navigateur ; ce qui se teste ici, c'est ou il a le
     * droit de paraitre, et qu'il propose bien les trois sorties.
     */
    public function test_le_seuil_parait_sur_le_site(): void
    {
        $this->seed();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('seuil-olimpia', escape: false)
            ->assertSee('Entrer sur le site')
            ->assertSee('Ne plus afficher')
            ->assertSee('Fermer et entrer sur le site');
    }

    /** Ses deux phrases viennent d'un reglage, comme le reste de sa parole. */
    public function test_le_texte_du_seuil_se_modifie_depuis_les_reglages(): void
    {
        $this->seed();

        Setting::where('cle', 'hommage.seuil')->firstOrFail()
            ->update(['valeur' => 'Deux mots, et rien de plus.']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Deux mots, et rien de plus.');
    }

    /** On n'annonce pas a quelqu'un ce qu'il est deja en train de lire. */
    public function test_le_seuil_ne_parait_pas_sur_sa_propre_page(): void
    {
        $this->seed();

        $this->get(route('hommage'))
            ->assertOk()
            ->assertDontSee('seuil-olimpia', escape: false);
    }

    /**
     * Une famille en train de verser un acompte n'a pas a voir surgir autre
     * chose. C'est la seule page du site ou l'on demande de l'argent.
     */
    public function test_le_seuil_ne_parait_pas_pendant_un_paiement(): void
    {
        $this->seed();

        $chaton = \App\Models\Kitten::where('statut', \App\Enums\KittenStatus::Disponible)->firstOrFail();

        $reservation = \App\Models\Reservation::create([
            'kitten_id'        => $chaton->id,
            'prenom'           => 'Camille',
            'nom'              => 'Dupuis',
            'email'            => 'camille@example.test',
            'acompte_centimes' => 30000,
            'expire_le'        => now()->addDays(7),
        ]);

        $this->get($reservation->lienPublic())
            ->assertOk()
            ->assertDontSee('seuil-olimpia', escape: false);
    }

    /* ── la vue plein ecran ──────────────────────────────────────── */

    /**
     * La regle qui la referme.
     *
     * Le display:none qu'un navigateur pose sur un element [hidden] vient de
     * sa propre feuille de style, et le display:flex de #lb, declare sur un
     * selecteur d'id, l'emporte. Sans #lb[hidden]{display:none}, la vue plein
     * ecran ne se refermait jamais : elle restait par-dessus le site, et plus
     * rien n'etait cliquable nulle part.
     *
     * Ca s'est produit. Une ligne de CSS qu'on ne remarque pas en relecture, et
     * tout le site devient inutilisable des le premier clic sur une image :
     * elle merite d'etre tenue par un test, meme grossier.
     */
    public function test_la_vue_plein_ecran_sait_se_refermer(): void
    {
        $charte = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('#lb[hidden]', $charte,
            'Sans cette règle, la vue plein écran reste ouverte par-dessus le site.');
    }

    /**
     * Et celle qui la garde en place : #lb ne doit pas figurer dans la liste de
     * ce qui « reste au-dessus du grain », qui repose position:relative. Elle
     * s'y trouvait, ce qui couchait la vue plein ecran dans le flux, en bas de
     * page, au lieu de couvrir l'ecran.
     */
    public function test_la_vue_plein_ecran_reste_fixe(): void
    {
        $charte = file_get_contents(resource_path('css/app.css'));

        $this->assertStringNotContainsString('.bande-photo,#lb,', $charte,
            '#lb est revenu dans la liste des éléments en position:relative.');
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
