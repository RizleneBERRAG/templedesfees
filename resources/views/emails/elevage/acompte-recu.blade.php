{{-- L'acompte d'une reservation est arrive. --}}
@component('emails.lettre', ['objetLisible' => 'Acompte reçu'])

<p style="margin:0 0 18px;font-size:17px;">
    L’acompte de <strong>{{ $reservation->nomComplet() }}</strong> est enregistré.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-collapse:collapse;margin-bottom:22px;">
    @include('emails._ligne', ['intitule' => 'Chaton',   'valeur' => $reservation->kitten?->nom])
    @include('emails._ligne', ['intitule' => 'Acompte',  'valeur' => $reservation->acompteFormate()])
    @include('emails._ligne', ['intitule' => 'Solde',    'valeur' => $reservation->soldeFormate()])
    @include('emails._ligne', ['intitule' => 'Facture',  'valeur' => $reservation->reference()])
</table>

<p style="margin:0 0 22px;padding:12px 16px;background:#F7F4EC;border:1px solid #E3DECF;
          font-size:14px;color:#413B31;">
    Le chaton est passé en <strong>réservé</strong> sur le site, et la facture d’acompte
    porte un numéro définitif.
</p>

<p style="margin:0;">
    <a href="{{ $lien }}" style="display:inline-block;background:#14100A;color:#D9B26A;
       text-decoration:none;padding:12px 24px;font-size:14px;letter-spacing:.08em;
       text-transform:uppercase;">Ouvrir la réservation</a>
</p>

@endcomponent
