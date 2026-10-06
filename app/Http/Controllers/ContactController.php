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
     * Ils visaient la commune et non le numero, pour ne pas publier l'adresse
     * d'un clic. C'etait un faux scrupule : l'adresse complete figure deja au
     * pied de chaque page, dans les courriels et dans les donnees structurees
     * livrees a Google. La demi-mesure ne protegeait rien et obligeait les
     * familles a chercher la fin du chemin toutes seules.
     *
     * Depuis le 6 octobre 2026, l'elevage assume son adresse partout.
     *
     * Chaque lien reste remplacable par un reglage, par exemple par celui de
     * la fiche Google une fois qu'elle sera validee.
     *
     * @return array<string,string>
     */
    private static function itineraires(): array
    {
        $adresse = trim(implode(' ', array_filter([
            Setting::get('elevage.adresse', '24 chemin Saint-Charles'),
            Setting::get('elevage.code_postal', '26210'),
            Setting::get('elevage.ville', 'Lapeyrouse-Mornay'),
            'France',
        ])));

        return [
            'commune' => $adresse,
            'google'  => Setting::get('contact.itineraire_google')
                ?: 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($adresse),
            'waze'    => Setting::get('contact.itineraire_waze')
                ?: 'https://www.waze.com/ul?navigate=yes&q='.urlencode($adresse),
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
