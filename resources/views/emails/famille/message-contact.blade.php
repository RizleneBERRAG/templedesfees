{{--
    L'accuse de reception d'un message.

    Il recopie le message envoye : une famille qui ecrit depuis un formulaire
    n'en garde aucune trace, et se demande ensuite si elle a bien appuye.
--}}
@component('emails.lettre', ['objetLisible' => 'Votre message'])

<p style="margin:0 0 16px;">Bonjour {{ $contact->prenom }},</p>

<p style="margin:0 0 16px;">
    Votre message est bien arrivé. Nous vous répondons <strong>sous 48 heures</strong>.
</p>

<p style="margin:0 0 8px;font-size:12.5px;letter-spacing:.06em;text-transform:uppercase;color:#8D8575;">
    Ce que vous nous avez écrit
</p>

<div style="border-left:3px solid #D9B26A;padding:2px 0 2px 16px;margin-bottom:22px;color:#413B31;">
    {!! nl2br(e($contact->message)) !!}
</div>

<p style="margin:0 0 16px;">À très vite,</p>

<p style="margin:0;color:#6B6456;">Kevin</p>

@endcomponent
