<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReview;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Depot d'un avis par un visiteur.
     *
     * L'avis arrive TOUJOURS en attente : est_publie a false, quoi qu'envoie le
     * formulaire. Rien ne parait sans relecture de l'elevage — c'est ce que la
     * page Mentions legales promet, et c'est aussi la seule defense serieuse
     * contre le spam et la diffamation.
     */
    public function store(StoreReview $request)
    {
        $avis = Review::create([
            ...$request->safe()->only(['prenom', 'email', 'note', 'texte']),
            'source'          => 'site',
            'est_publie'      => false,
            'consentement_le' => now(),
            'publie_le'       => now(),
        ]);

        // TODO prevenir l'elevage qu'un avis attend, une fois le SMTP configure.

        return redirect()
            ->route('contact')
            ->with('succes_avis', "Merci {$avis->prenom}, votre avis est bien arrivé. Il sera publié après relecture.")
            ->withFragment('avis');
    }
}
