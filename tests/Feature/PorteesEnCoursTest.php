<?php

namespace Tests\Feature;

use App\Enums\KittenStatus;
use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * La page des chatons montre TOUTES les portees en cours.
 *
 * Elle n'en montrait qu'une — la plus recente — et rangeait les autres parmi
 * les portees passees. L'elevage a eu trois portees la meme saison : sept
 * chatons sur dix etaient donc presentes comme de l'archive alors qu'ils
 * attendaient une famille. Personne ne l'a vu avant la mise en ligne, parce
 * que le jeu de donnees de test ne portait qu'une seule portee — ce test en
 * fabrique donc les siennes.
 *
 * Une portee n'est passee que lorsque tous ses chatons sont partis.
 */
class PorteesEnCoursTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0: Litter, 1: Litter} deux portees publiees, en cours */
    private function deuxPortees(): array
    {
        $pere = Cat::where('sexe', 'male')->firstOrFail();
        $meres = Cat::where('sexe', 'femelle')->take(2)->get();

        $portees = [];
        foreach (['Portée Z — Essai un', 'Portée Y — Essai deux'] as $i => $code) {
            $portee = Litter::create([
                'code'           => $code,
                'slug'           => Str::slug($code),
                'pere_id'        => $pere->id,
                'mere_id'        => $meres[$i]->id,
                'date_naissance' => now()->subWeeks(9 - $i),
                'nb_chatons'     => 2,
                'est_publiee'    => true,
            ]);

            foreach (['Un', 'Deux'] as $n) {
                $nom = "Chaton {$n} {$i}";
                Kitten::create([
                    'litter_id'  => $portee->id,
                    'nom'        => $nom,
                    'slug'       => Str::slug($nom),
                    'sexe'       => 'male',
                    'robe'       => 'Red',
                    'statut'     => KittenStatus::Disponible,
                    'est_publie' => true,
                ]);
            }

            $portees[] = $portee->fresh();
        }

        return $portees;
    }

    public function test_toutes_les_portees_en_cours_sont_affichees(): void
    {
        $this->seed();
        [$une, $deux] = $this->deuxPortees();

        $reponse = $this->get('/chatons')->assertOk();

        foreach ([$une, $deux] as $portee) {
            $reponse->assertSee($portee->code, escape: false);

            foreach ($portee->kittens()->publies()->get() as $chaton) {
                $reponse->assertSee($chaton->nom, escape: false);
                $reponse->assertSee('/chatons/'.$chaton->slug, escape: false);
            }
        }
    }

    /** Le compteur « Tous » couvre l'ensemble, pas la seule premiere portee. */
    public function test_le_compte_total_couvre_toutes_les_portees(): void
    {
        $this->seed();
        $this->deuxPortees();

        $attendu = Kitten::publies()
            ->whereHas('litter', fn ($q) => $q->publiees()->enCours())
            ->count();

        $this->assertGreaterThan(4, $attendu);

        $this->get('/chatons')
            ->assertOk()
            ->assertSee('Tous ('.$attendu.')', escape: false);
    }

    /**
     * Une portee entierement adoptee quitte la section « en cours ».
     *
     * C'est la contrepartie : si plus rien n'en sortait, la page finirait par
     * empiler toutes les portees de l'elevage.
     */
    public function test_une_portee_entierement_adoptee_quitte_les_portees_en_cours(): void
    {
        $this->seed();
        [$partie, $restante] = $this->deuxPortees();

        $partie->kittens()->update(['statut' => KittenStatus::Adopte]);

        $enCours = Litter::publiees()->enCours()->pluck('id');

        $this->assertNotContains($partie->id, $enCours,
            'Une portée dont tous les chatons sont partis n’est plus en cours.');
        $this->assertContains($restante->id, $enCours);

        $this->get('/chatons')
            ->assertOk()
            ->assertSee($restante->code, escape: false);
    }
}
