@php
    $nom   = \App\Models\Setting::get('elevage.nom', 'La Chatterie du Temple des Fées');
    $tel   = \App\Models\Setting::get('contact.telephone');
    $mail  = \App\Models\Setting::get('contact.email');
@endphp
{{--
    La mise en page des courriels.

    Tout est écrit en style dans l'attribut, et la structure repose sur des
    tableaux : ce n'est pas de la négligence, c'est ce que comprennent les
    logiciels de messagerie. Outlook ignore une feuille de style externe, Gmail
    coupe ce qu'il ne reconnaît pas, et une mise en page en flex ou en grid
    s'effondre dans la moitié des boîtes de réception.

    Fond clair, contrairement au site : un courriel sombre arrive mal partout,
    et se lit mal une fois imprimé.
--}}
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $objetLisible ?? $nom }}</title>
</head>
<body style="margin:0;padding:0;background:#F4F2ED;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background:#F4F2ED;padding:28px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:580px;background:#FFFFFF;border:1px solid #E3DECF;">

                {{-- L'en-tête : le nom de l'élevage, rien d'autre. --}}
                <tr>
                    <td style="background:#14100A;padding:22px 28px;">
                        <div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;
                                    letter-spacing:.08em;color:#D9B26A;text-transform:uppercase;">
                            {{ $nom }}
                        </div>
                        <div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;
                                    letter-spacing:.18em;color:#9C937F;text-transform:uppercase;margin-top:5px;">
                            Maine Coon · Lapeyrouse-Mornay
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px 28px 26px;font-family:Arial,Helvetica,sans-serif;
                               font-size:15px;line-height:1.62;color:#2B2721;">
                        {{ $slot }}
                    </td>
                </tr>

                {{-- Le pied : comment nous joindre, pour de vrai. --}}
                <tr>
                    <td style="border-top:1px solid #E3DECF;padding:18px 28px;
                               font-family:Arial,Helvetica,sans-serif;font-size:12.5px;
                               line-height:1.7;color:#6B6456;">
                        @if($tel)
                            {{ $tel }}@if($mail) &nbsp;·&nbsp; @endif
                        @endif
                        @if($mail)<a href="mailto:{{ $mail }}" style="color:#8A6D3B;">{{ $mail }}</a>@endif
                        <br>
                        24 chemin Saint-Charles · 26210 Lapeyrouse-Mornay
                    </td>
                </tr>

            </table>

            <div style="max-width:580px;margin-top:14px;font-family:Arial,Helvetica,sans-serif;
                        font-size:11.5px;line-height:1.6;color:#8D8575;text-align:center;">
                Ce message vous est envoyé parce que vous nous avez écrit depuis notre site.
            </div>

        </td>
    </tr>
</table>

</body>
</html>
