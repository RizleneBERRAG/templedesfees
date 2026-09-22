<?php

namespace App\Http\Controllers;

use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Http\Request;

class KittenController extends Controller
{
    public function index(Request $request)
    {
        $portee = Litter::publiees()
            ->with(['pere', 'mere', 'events'])
            ->orderByDesc('date_naissance')
            ->firstOrFail();

        $chatons = $portee->kittens()->publies()->get();

        $statut = $request->query('statut');
        $filtres = $chatons->countBy(fn (Kitten $k) => $k->statut->value);

        return view('pages.kittens.index', [
            'portee'    => $portee,
            'chatons'   => $statut
                ? $chatons->filter(fn (Kitten $k) => $k->statut->value === $statut)
                : $chatons,
            'total'     => $chatons->count(),
            'filtres'   => $filtres,
            'statut'    => $statut,
            'archives'  => Litter::publiees()
                ->whereKeyNot($portee->id)
                ->withCount('kittens')
                ->orderByDesc('date_naissance')
                ->get(),
        ]);
    }

    /**
     * Le binding ne resout que les fiches publiees : une fiche a laquelle il manque
     * le numero ICAD ou le numero de portee LOOF renvoie un 404, jamais une page
     * incomplete. Aucune donnee sur la famille adoptante n'est exposee ici.
     */
    public function show(Kitten $kitten)
    {
        // Le drapeau seul ne suffit pas : on revalide la regle legale sur la fiche
        // resolue, pour que le 404 tienne meme si est_publie a ete pose par un
        // chemin qui contourne KittenObserver. Cf. Kitten::scopePublies().
        abort_unless($kitten->est_publie && $kitten->estPubliable(), 404);

        $kitten->load(['litter.pere.healthTests', 'litter.mere.healthTests', 'litter.events', 'photos']);

        return view('pages.kittens.show', [
            'chaton'  => $kitten,
            'portee'  => $kitten->litter,
            'fratrie' => $kitten->litter->kittens()->publies()->whereKeyNot($kitten->id)->get(),
        ]);
    }
}
