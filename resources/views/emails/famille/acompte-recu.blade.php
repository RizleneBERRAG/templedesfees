{{--
    Le recu d'acompte envoye a la famille.

    Il porte le lien de la facture, pas la facture elle-meme : une piece
    jointe se perd, un lien se retrouve, et la page se met a jour si une
    information change avant le depart.
--}}
@component('emails.lettre', ['objetLisible' => 'Votre acompte est bien arrivé'])

<p style="margin:0 0 16px;">Bonjour {{ $reservation->prenom }},</p>

<p style="margin:0 0 16px;">
    Nous avons bien reçu votre acompte de <strong>{{ $reservation->acompteFormate() }}</strong>.
    @if($reservation->kitten)
        <strong>{{ $reservation->kitten->nom }}</strong> vous est réservé — il n’est plus proposé à personne d’autre.
    @endif
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-collapse:collapse;margin-bottom:22px;">
    @include('emails._ligne', ['intitule' => 'Facture', 'valeur' => $reservation->reference()])
    @include('emails._ligne', ['intitule' => 'Acompte', 'valeur' => $reservation->acompteFormate()])
    @include('emails._ligne', ['intitule' => 'Solde',   'valeur' => $reservation->soldeFormate()])
    @include('emails._ligne', [
        'intitule' => 'Départ',
        'valeur'   => $reservation->departPrevu()?->translatedFormat('l j F Y'),
    ])
</table>

<p style="margin:0 0 22px;">
    <a href="{{ $facture }}" style="display:inline-block;background:#14100A;color:#D9B26A;
       text-decoration:none;padding:12px 24px;font-size:14px;letter-spacing:.08em;
       text-transform:uppercase;">Voir votre facture</a>
</p>

<p style="margin:0 0 16px;font-size:14px;color:#6B6456;">
    Le solde se règle au départ du chaton. D’ici là, vous aurez de ses nouvelles.
</p>

<p style="margin:0;color:#6B6456;">Kevin</p>

@endcomponent
