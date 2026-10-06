<?php

namespace App\Http\Controllers;

use App\Filament\Resources\AdoptionRequests\AdoptionRequestResource;
use App\Http\Requests\StoreAdoptionRequest;
use App\Mail\Lettre;
use App\Models\AdoptionRequest;
use App\Models\Kitten;
use App\Support\Facteur;

class AdoptionController extends Controller
{
    public function create()
    {
        return view('pages.adoption', [
            'disponibles' => Kitten::publies()->disponibles()->with('litter')->get(),
        ]);
    }

    public function store(StoreAdoptionRequest $request)
    {
        $demande = AdoptionRequest::create($request->safe()->except('rgpd', 'site'));
        $demande->load('kitten');

        $this->prevenir($demande);

        return redirect()
            ->route('adoption.create')
            ->with('succes', "Merci {$demande->prenom}, votre demande est bien arrivée. Nous vous répondons sous 48 heures.");
    }

    /**
     * Les deux courriels d'une demande : l'elevage, puis la famille.
     *
     * Ils partent par le facteur, qui avale les pannes d'envoi : le dossier
     * est deja enregistre a ce stade, et une adoption ne se perd pas parce
     * qu'un serveur de courriel est tombe.
     *
     * Celui de l'elevage porte l'adresse de la famille en « Repondre a » :
     * un clic sur « Repondre » ecrit a la bonne personne, sans aller
     * rechercher l'adresse dans l'espace de gestion.
     */
    private function prevenir(AdoptionRequest $demande): void
    {
        Facteur::porter(Facteur::elevage(), new Lettre(
            vue:          'elevage.demande-adoption',
            objet:        "Demande d’adoption — {$demande->prenom} {$demande->nom}",
            donnees:      [
                'demande' => $demande,
                'lien'    => AdoptionRequestResource::getUrl('edit', ['record' => $demande], panel: 'admin'),
            ],
            repondreA:    $demande->email,
            repondreANom: trim("{$demande->prenom} {$demande->nom}"),
        ));

        Facteur::porter($demande->email, new Lettre(
            vue:     'famille.demande-adoption',
            objet:   'Votre demande d’adoption est bien arrivée',
            donnees: ['demande' => $demande],
        ));
    }
}
