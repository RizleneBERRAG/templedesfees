<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdoptionRequest;
use App\Models\AdoptionRequest;
use App\Models\Kitten;

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

        // TODO brancher la notification a l'elevage une fois le SMTP configure :
        // Mail::to(config('mail.elevage'))->send(new NouvelleDemandeAdoption($demande));

        return redirect()
            ->route('adoption.create')
            ->with('succes', "Merci {$demande->prenom}, votre demande est bien arrivée. Nous vous répondons sous 48 heures.");
    }
}
