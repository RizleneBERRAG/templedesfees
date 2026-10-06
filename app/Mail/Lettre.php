<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Une lettre du site.
 *
 * Sept courriels partent d'ici — quatre vers l'elevage, trois vers les
 * familles — et ils ne different que par leur objet et leur contenu. Sept
 * classes qui se ressembleraient a une ligne pres ne diraient rien de plus
 * que celle-ci : ce qui change vit dans les vues de resources/views/emails,
 * ou on le lit en entier sans ouvrir de PHP.
 *
 * L'adresse de reponse est posee quand elle a un sens : une notification
 * envoyee a l'eleveur porte celle de la famille, pour qu'un « Repondre »
 * aille au bon endroit sans copier-coller.
 */
class Lettre extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * ATTENTION aux noms passes dans $donnees.
     *
     * Laravel injecte d'office un $message dans toute vue de courriel : le
     * sien, l'enveloppe, qui sert a joindre une piece ou poser un en-tete. Une
     * donnee nommee $message est donc silencieusement ecrasee, et la vue
     * echoue au rendu — pas a l'envoi, ce qui la rend invisible aux tests qui
     * se contentent de verifier qu'une lettre est partie.
     *
     * C'est arrive le 5 octobre 2026 sur les deux lettres de contact, en
     * production. Elles passent depuis par $contact.
     *
     * @param  array<string, mixed>  $donnees  jamais de cle « message »
     */
    public function __construct(
        public string $vue,
        public string $objet,
        public array $donnees = [],
        public ?string $repondreA = null,
        public ?string $repondreANom = null,
    ) {}

    public function envelope(): Envelope
    {
        $enveloppe = new Envelope(subject: $this->objet);

        if ($this->repondreA) {
            $enveloppe = new Envelope(
                subject: $this->objet,
                replyTo: [new Address($this->repondreA, $this->repondreANom ?? '')],
            );
        }

        return $enveloppe;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.'.$this->vue,
            with: $this->donnees,
        );
    }
}
