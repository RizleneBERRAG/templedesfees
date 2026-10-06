{{--
    Un avis attend d'etre relu.

    Il n'est PAS publie a ce stade, et le courriel le dit : la page Mentions
    legales promet une relecture, et un eleveur qui croirait l'avis deja en
    ligne ne viendrait jamais le valider.
--}}
@component('emails.lettre', ['objetLisible' => 'Un avis attend votre relecture'])

<p style="margin:0 0 18px;font-size:17px;">
    <strong>{{ $avis->prenom }}</strong> a laissé un avis
    — {{ str_repeat('★', $avis->note) }}{{ str_repeat('☆', 5 - $avis->note) }} ({{ $avis->note }}/5).
</p>

<div style="border-left:3px solid #D9B26A;padding:2px 0 2px 16px;margin-bottom:22px;color:#413B31;">
    {!! nl2br(e($avis->texte)) !!}
</div>

<p style="margin:0 0 22px;padding:12px 16px;background:#F7F4EC;border:1px solid #E3DECF;
          font-size:14px;color:#413B31;">
    Il <strong>n’est pas encore en ligne</strong>. Il paraîtra sur le site quand vous
    l’aurez publié depuis votre espace de gestion.
</p>

<p style="margin:0;">
    <a href="{{ $lien }}" style="display:inline-block;background:#14100A;color:#D9B26A;
       text-decoration:none;padding:12px 24px;font-size:14px;letter-spacing:.08em;
       text-transform:uppercase;">Relire l’avis</a>
</p>

@endcomponent
