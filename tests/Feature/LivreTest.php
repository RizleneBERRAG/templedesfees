<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Le livre de la maison, et la seule chose qui puisse encore le casser.
 *
 * Une page de livre a une hauteur, son texte n'en a pas. Quand le second
 * depasse la premiere, l'overflow:hidden mange proprement ce qui deborde :
 * rien ne previent, rien ne se voit, et une phrase disparait du site. C'est
 * arrive une fois, sous 345 px, ou trois pages sur six perdaient leur
 * derniere ligne.
 *
 * En lecture une page a la fois, ce n'est plus possible : la hauteur vient
 * desormais de la page la plus chargee, et le livre s'allonge avec son texte.
 * En lecture deux pages, elle reste imposee par le format — le tour de page en
 * trois dimensions en depend — et c'est donc la que la limite subsiste.
 *
 * D'ou ce test. Il ne garde pas l'eleveur : les six textes vivent dans
 * config/chatterie.php, personne ne peut les changer depuis l'administration.
 * Il garde celui qui les modifiera dans le code, le jour ou il le fera, et lui
 * evite de publier une phrase coupee sans le savoir.
 *
 * Le budget est mesure, pas devine. Dans le cas le plus serre de la lecture
 * deux pages — autour de 1280 px, la ou la page est la plus large donc la plus
 * basse — il reste neuf lignes libres sous le texte le plus long, a une
 * cinquantaine de signes la ligne. Le plus long des six en fait 298 : la page
 * en accepterait donc environ 750. La limite est posee a 600, ce qui laisse
 * trois lignes de marge pour les ecarts de police et de largeur.
 */
class LivreTest extends TestCase
{
    /** Ce que la page la plus basse accepte, moins trois lignes de securite. */
    private const SIGNES_MAX = 600;

    /** Un titre qui passe a la ligne coute le double d'une ligne de texte. */
    private const SIGNES_MAX_TITRE = 40;

    public function test_aucun_texte_du_livre_ne_depasse_ce_que_la_page_peut_tenir(): void
    {
        $pages = config('chatterie.livre');

        $this->assertNotEmpty($pages, 'Le livre de la maison est vide.');

        foreach ($pages as $rang => $page) {
            $longueur = mb_strlen($page['texte']);

            $this->assertLessThanOrEqual(
                self::SIGNES_MAX,
                $longueur,
                sprintf(
                    'La page %d du livre (« %s ») fait %d signes, au-dela des %d '
                    ."que la page tient en lecture deux pages.\n"
                    .'Passe cette longueur, la fin du texte est coupee sans que rien '
                    ."ne le signale a l'ecran.",
                    $rang + 1,
                    $page['titre'],
                    $longueur,
                    self::SIGNES_MAX,
                ),
            );
        }
    }

    public function test_les_titres_du_livre_restent_courts(): void
    {
        foreach (config('chatterie.livre') as $rang => $page) {
            $this->assertLessThanOrEqual(
                self::SIGNES_MAX_TITRE,
                mb_strlen($page['titre']),
                sprintf(
                    'Le titre de la page %d du livre est trop long : a cette taille '
                    .'il passe a la ligne, et une ligne de titre mange la place de '
                    .'deux lignes de texte.',
                    $rang + 1,
                ),
            );
        }
    }

    public function test_chaque_page_du_livre_porte_bien_un_titre_et_un_texte(): void
    {
        foreach (config('chatterie.livre') as $rang => $page) {
            $this->assertArrayHasKey('titre', $page, "Page {$rang} sans titre.");
            $this->assertArrayHasKey('texte', $page, "Page {$rang} sans texte.");
            $this->assertNotSame('', trim($page['titre']));
            $this->assertNotSame('', trim($page['texte']));
        }
    }
}
