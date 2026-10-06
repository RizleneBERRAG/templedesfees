{{--
    Une ligne du tableau recapitulatif : l'intitule a gauche, la valeur a
    droite. Rien ne s'affiche quand la valeur est vide — une demande sans
    telephone ne doit pas montrer une case « Telephone » vide.
--}}
@if(filled($valeur ?? null))
<tr>
    <td style="padding:5px 12px 5px 0;vertical-align:top;white-space:nowrap;
               font-size:12.5px;letter-spacing:.06em;text-transform:uppercase;color:#8D8575;">
        {{ $intitule }}
    </td>
    <td style="padding:5px 0;vertical-align:top;font-size:14.5px;color:#2B2721;">
        {!! nl2br(e($valeur)) !!}
    </td>
</tr>
@endif
