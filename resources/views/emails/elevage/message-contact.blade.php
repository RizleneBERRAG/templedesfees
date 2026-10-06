{{-- Un visiteur a ecrit depuis la page Contact. --}}
@component('emails.lettre', ['objetLisible' => 'Nouveau message depuis le site'])

<p style="margin:0 0 18px;font-size:17px;">
    <strong>{{ $contact->prenom }} {{ $contact->nom }}</strong> vous a écrit.
</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-collapse:collapse;margin-bottom:22px;">
    @include('emails._ligne', ['intitule' => 'Reçu le',   'valeur' => $contact->created_at->translatedFormat('l j F Y à H\hi')])
    @include('emails._ligne', ['intitule' => 'Objet',     'valeur' => $contact->objetLibelle()])
    @include('emails._ligne', ['intitule' => 'Téléphone', 'valeur' => $contact->telephone])
    @include('emails._ligne', ['intitule' => 'Courriel',  'valeur' => $contact->email])
</table>

<div style="border-left:3px solid #D9B26A;padding:2px 0 2px 16px;margin-bottom:22px;color:#413B31;">
    {!! nl2br(e($contact->message)) !!}
</div>

<p style="margin:0 0 6px;">
    <a href="{{ $lien }}" style="display:inline-block;background:#14100A;color:#D9B26A;
       text-decoration:none;padding:12px 24px;font-size:14px;letter-spacing:.08em;
       text-transform:uppercase;">Ouvrir le message</a>
</p>

<p style="margin:16px 0 0;font-size:13px;color:#8D8575;">
    Répondre à ce message écrit directement à {{ $contact->prenom }}.
</p>

@endcomponent
