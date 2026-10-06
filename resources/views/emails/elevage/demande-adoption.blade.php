{{--
    Une famille vient de deposer un dossier d'adoption.

    Le courriel porte l'adresse de la famille en « Repondre a » : un clic sur
    « Repondre » dans Outlook ecrit a la bonne personne, sans aller rechercher
    l'adresse dans l'espace de gestion.
--}}
@component('emails.lettre', ['objetLisible' => 'Nouvelle demande d’adoption'])

<p style="margin:0 0 18px;font-size:17px;">
    <strong>{{ $demande->prenom }} {{ $demande->nom }}</strong> a déposé une demande d’adoption.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-collapse:collapse;margin-bottom:22px;">
    @include('emails._ligne', ['intitule' => 'Reçue le',   'valeur' => $demande->created_at->translatedFormat('l j F Y à H\hi')])
    @include('emails._ligne', ['intitule' => 'Téléphone',  'valeur' => $demande->telephone])
    @include('emails._ligne', ['intitule' => 'Courriel',   'valeur' => $demande->email])
    @include('emails._ligne', ['intitule' => 'Code postal','valeur' => $demande->code_postal])
    @include('emails._ligne', ['intitule' => 'Chaton',     'valeur' => $demande->kitten?->nom ?? 'Pas de chaton précis'])
    @include('emails._ligne', ['intitule' => 'Souhait',    'valeur' => $demande->souhait])
    @include('emails._ligne', ['intitule' => 'Logement',   'valeur' => $demande->logement])
    @include('emails._ligne', ['intitule' => 'Animaux',    'valeur' => $demande->autres_animaux])
    @include('emails._ligne', ['intitule' => 'Présence',   'valeur' => $demande->presence])
    @include('emails._ligne', ['intitule' => 'Expérience', 'valeur' => $demande->experience])
</table>

@if(filled($demande->message))
    <div style="border-left:3px solid #D9B26A;padding:2px 0 2px 16px;margin-bottom:22px;color:#413B31;">
        {!! nl2br(e($demande->message)) !!}
    </div>
@endif

<p style="margin:0 0 6px;">
    <a href="{{ $lien }}" style="display:inline-block;background:#14100A;color:#D9B26A;
       text-decoration:none;padding:12px 24px;font-size:14px;letter-spacing:.08em;
       text-transform:uppercase;">Ouvrir la demande</a>
</p>

<p style="margin:16px 0 0;font-size:13px;color:#8D8575;">
    Répondre à ce message écrit directement à {{ $demande->prenom }}.
</p>

@endcomponent
