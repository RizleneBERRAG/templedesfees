<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Reviews\ReviewResource;
use App\Http\Requests\StoreReview;
use App\Mail\Lettre;
use App\Models\Review;
use App\Support\Facteur;

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

        /*
         * L'elevage seul est prevenu. Le visiteur ne recoit rien : lui ecrire
         * « votre avis est enregistre » juste avant de ne pas le publier serait
         * une promesse qu'on ne tient pas, et la page le lui a deja dit.
         */
        Facteur::porter(Facteur::elevage(), new Lettre(
            vue:          'elevage.avis-en-attente',
            objet:        "Avis à relire — {$avis->prenom} ({$avis->note}/5)",
            donnees:      [
                'avis' => $avis,
                'lien' => ReviewResource::getUrl('edit', ['record' => $avis], panel: 'admin'),
            ],
            repondreA:    $avis->email,
            repondreANom: $avis->prenom,
        ));

        return redirect()
            ->route('contact')
            ->with('succes_avis', "Merci {$avis->prenom}, votre avis est bien arrivé. Il sera publié après relecture.")
            ->withFragment('avis');
    }
}
