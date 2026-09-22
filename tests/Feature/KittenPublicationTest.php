<?php

namespace Tests\Feature;

use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La regle de l'article L214-8-1 : pas de numero ICAD et de numero de portee LOOF,
 * pas de fiche publiee. C'est la seule contrainte du projet qui soit legale et non
 * editoriale, et elle doit tenir quel que soit le chemin d'ecriture — y compris les
 * mises a jour de masse, qui ne declenchent aucun observer Eloquent.
 */
class KittenPublicationTest extends TestCase
{
    use RefreshDatabase;

    private function portee(?string $loof = 'LOOF-2026-0001'): Litter
    {
        $pere = Cat::create(['nom' => 'Uzumaki', 'slug' => 'uzumaki', 'sexe' => 'male', 'role' => 'etalon']);
        $mere = Cat::create(['nom' => 'Wendy', 'slug' => 'wendy', 'sexe' => 'femelle', 'role' => 'reproductrice']);

        return Litter::create([
            'code'               => 'Portee X',
            'slug'               => 'portee-x-2026',
            'pere_id'            => $pere->id,
            'mere_id'            => $mere->id,
            'date_naissance'     => now()->subWeeks(14)->toDateString(),
            'loof_portee_numero' => $loof,
            'est_publiee'        => true,
        ]);
    }

    private function chaton(Litter $portee, ?string $icad, bool $publie = true, string $nom = 'Xena'): Kitten
    {
        return Kitten::create([
            'litter_id'   => $portee->id,
            'nom'         => $nom,
            'slug'        => \Illuminate\Support\Str::slug($nom),
            'sexe'        => 'femelle',
            'statut'      => 'disponible',
            'icad_numero' => $icad,
            'est_publie'  => $publie,
        ]);
    }

    public function test_une_fiche_complete_est_bien_publiee_et_accessible(): void
    {
        $chaton = $this->chaton($this->portee(), '250269000000001');

        $this->assertTrue($chaton->fresh()->est_publie, 'Une fiche complete doit rester publiee.');
        $this->assertSame(1, Kitten::publies()->count());
        $this->get('/chatons/'.$chaton->slug)->assertOk();
    }

    public function test_l_observer_repasse_en_brouillon_une_fiche_sans_numero_icad(): void
    {
        $chaton = $this->chaton($this->portee(), null, publie: true);

        $this->assertFalse($chaton->fresh()->est_publie);
        $this->assertSame(["numéro d'identification ICAD du chaton"], $chaton->mentionsManquantes());
    }

    /**
     * Le cas que le drapeau seul ne couvrait pas : Eloquent ne declenche ni saving ni
     * saved sur une mise a jour de masse. C'est exactement ce que fait une action
     * groupee de back-office du type « publier la selection ».
     */
    public function test_une_mise_a_jour_de_masse_ne_peut_pas_publier_une_fiche_non_conforme(): void
    {
        $chaton = $this->chaton($this->portee(), null, publie: false);

        Kitten::query()->update(['est_publie' => true]);

        $this->assertTrue($chaton->fresh()->est_publie, 'Le drapeau a bien ete force en base : le test porte sur ce cas.');
        $this->assertSame(0, Kitten::publies()->count(), 'Une fiche sans numero ICAD ne doit jamais sortir du scope publies().');
        $this->get('/chatons/'.$chaton->slug)->assertNotFound();
    }

    /**
     * L'autre angle mort : le numero vit sur la portee, et rien ne repasse ses chatons
     * en brouillon quand on le vide. La revalidation a la lecture s'en charge.
     */
    public function test_vider_le_numero_de_portee_depublie_les_chatons_de_la_portee(): void
    {
        $portee = $this->portee();
        $chaton = $this->chaton($portee, '250269000000001');
        $this->assertSame(1, Kitten::publies()->count());

        Litter::query()->update(['loof_portee_numero' => null]);

        $this->assertSame(0, Kitten::publies()->count());
        $this->get('/chatons/'.$chaton->slug)->assertNotFound();
    }

    /** Le scope SQL et la regle PHP doivent dire la meme chose, sinon l'un des deux ment. */
    public function test_le_scope_sql_et_la_regle_php_restent_d_accord(): void
    {
        $complet   = $this->chaton($this->portee(), '250269000000001');
        $this->chaton($complet->litter, '', publie: true, nom: 'Yuki');

        $publiables = Kitten::publiables()->pluck('id')->all();

        foreach (Kitten::with('litter')->get() as $chaton) {
            $this->assertSame(
                $chaton->estPubliable(),
                in_array($chaton->id, $publiables, true),
                "Desaccord sur la fiche {$chaton->slug} entre estPubliable() et scopePubliables()."
            );
        }
    }
}
