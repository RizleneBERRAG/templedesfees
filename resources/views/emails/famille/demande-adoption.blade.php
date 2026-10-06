{{--
    L'accuse de reception d'une demande d'adoption.

    Il ne promet rien que l'elevage ne tienne : une reponse sous 48 heures,
    qui est deja ce qu'annonce la page. Il rappelle aussi la regle des
    visites, parce que c'est la question qui revient dans le message suivant
    une fois sur deux, et qu'elle surprend moins lue tot que refusee tard.
--}}
@component('emails.lettre', ['objetLisible' => 'Votre demande d’adoption'])

<p style="margin:0 0 16px;">Bonjour {{ $demande->prenom }},</p>

{{--
    Le nom du chaton n'est accole a aucun mot : une directive Blade collee au
    mot qui la precede n'est pas compilee, et part telle quelle dans la boite
    de reception.
--}}
@php
    /*
     * La phrase se compose ici plutot que dans le gabarit : une directive
     * Blade collee au mot qui la precede n'est pas compilee, et une directive
     * detachee laisse une espace devant la virgule.
     */
    $chaton = $demande->kitten
        ? ', et nous avons noté votre intérêt pour <strong>'.e($demande->kitten->nom).'</strong>'
        : '';
@endphp

<p style="margin:0 0 16px;">
    Votre demande est bien arrivée{!! $chaton !!}. Merci du temps que vous avez pris à la remplir.
</p>

<p style="margin:0 0 16px;">
    Nous la lisons en entier et nous vous répondons <strong>sous 48 heures</strong>.
    Nous ne plaçons pas les chatons au premier arrivé : nous cherchons le foyer qui
    convient à chacun, et cela demande de se parler.
</p>

<p style="margin:0 0 22px;padding:12px 16px;background:#F7F4EC;border:1px solid #E3DECF;
          font-size:14px;color:#413B31;">
    Une précision qui surprend souvent : <strong>les chatons ne reçoivent pas de visite</strong>.
    Tant qu’ils ne sont pas vaccinés, ce qu’un visiteur rapporte sous ses chaussures peut
    les emporter. Nous faisons connaissance par photos, nouvelles, appels et visio —
    et nous ne faisons pas d’exception.
</p>

<p style="margin:0 0 16px;">À très vite,</p>

<p style="margin:0;color:#6B6456;">Kevin</p>

@endcomponent
