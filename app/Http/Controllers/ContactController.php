<?php

namespace App\Http\Controllers;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Http\Requests\StoreContactMessage;
use App\Mail\Lettre;
use App\Models\ContactMessage;
use App\Models\Review;
use App\Models\Setting;
use App\Support\Facteur;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact', [
            'objets' => ContactMessage::OBJETS_PROPOSES,
            'points' => config('chatterie.carte'),
            'avis'   => Review::publies()->get(),
            'itineraire' => self::itineraires(),
        ]);
    }

    /**
     * Les liens d'itineraire, un par application de navigation.
     *
     * Tous visent la COMMUNE et non l'adresse exacte : la page annonce que
     * l'adresse est communiquee au rendez-vous, et un itineraire porte-a-porte
     * la publierait d'un clic. Chaque lien peut etre remplace par un reglage,
     * par exemple par la fiche Google de l'elevage.
     *
     * @return array<string,string>
     */
    private static function itineraires(): array
    {
        $commune = trim(Setting::get('elevage.ville', 'Lapeyrouse-Mornay').' '
            .Setting::get('elevage.code_postal', '26210').' France');

        return [
            'commune' => $commune,
            'google'  => Setting::get('contact.itineraire_google')
                ?: 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($commune),
            'waze'    => Setting::get('contact.itineraire_waze')
                ?: 'https://www.waze.com/ul?navigate=yes&q='.urlencode($commune),
        ];
    }

    public function store(StoreContactMessage $request)
    {
        $message = ContactMessage::create($request->safe()->except('rgpd', 'site'));

        $this->prevenir($message);

        return redirect()
            ->route('contact')
            ->with('succes', "Merci {$message->prenom}, votre message est bien arrivé. Nous vous répondons sous 48 heures.")
            ->withFragment('formulaire');
    }

    /**
     * Prevenir l'elevage, puis accuser reception a la famille.
     *
     * Le courriel de l'elevage porte l'adresse du visiteur en Â« Repondre a Â» :
     * un clic sur Â« Repondre Â» lui ecrit directement.
     */
    private function prevenir(ContactMessage $message): void
    {
        Facteur::porter(Facteur::elevage(), new Lettre(
            vue:          'elevage.message-contact',
            objet:        "Message du site — {$message->objetLibelle()}",
            donnees:      [
                'contact' => $message,
                'lien'    => ContactMessageResource::getUrl('edit', ['record' => $message], panel: 'admin'),
            ],
            repondreA:    $message->email,
            repondreANom: trim("{$message->prenom} {$message->nom}"),
        ));

        Facteur::porter($message->email, new Lettre(
            vue:     'famille.message-contact',
            objet:   'Votre message est bien arrivé',
            donnees: ['contact' => $message],
        ));
    }
}
