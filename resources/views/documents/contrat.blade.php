@extends('layouts.document')

{{--
    Le contrat de réservation.

    Il ne se retape pas : tout ce qu'il affiche vient de la fiche — le chaton et
    ses numéros, la portée et ses parents, la famille, les montants, les dates.
    Une réservation saisie une fois produit un contrat juste, et deux documents
    qui disent la même chose parce qu'ils lisent la même ligne.

    Seules les clauses viennent d'un réglage, pour que l'éleveuse puisse les
    reprendre avec son conseil sans demander une intervention.

    Ce contrat réserve ; il ne cède pas. La cession est signée au départ, avec
    le solde. Les clauses le disent en premier paragraphe.
--}}

@section('titre', 'Contrat de réservation '.$reservation->reference())
@section('genre', 'Contrat de réservation')
@section('numero', $reservation->reference())
@section('date', $reservation->created_at?->translatedFormat('j F Y'))

@php
    use App\Models\Setting;

    $portee     = $chaton?->litter;
    $conditions = Setting::get('legal.acompte');
    $clauses    = Setting::get('legal.contrat');
    $depart     = $reservation->departPrevu();
@endphp

@section('document')

    <section class="parties">
        <div>
            <span class="etiquette">L’élevage</span>
            <p class="nom">{{ Setting::get('elevage.nom') }}</p>
            <p class="fin">
                Représenté par
                {!! Setting::get('legal.directeur')
                    ? e(Setting::get('legal.directeur'))
                    : '<span class="a-completer">à compléter</span>' !!}<br>
                Ci-après « l’élevage »
            </p>
        </div>

        <div>
            <span class="etiquette">La famille</span>
            <p class="nom">{{ $reservation->nomComplet() }}</p>
            <p class="fin">
                @if($reservation->adresse){{ $reservation->adresse }}<br>@endif
                @if($reservation->code_postal || $reservation->ville)
                    {{ $reservation->code_postal }} {{ $reservation->ville }}<br>
                @endif
                {{ $reservation->email }}@if($reservation->telephone) · {{ $reservation->telephone }}@endif<br>
                Ci-après « la famille »
            </p>
        </div>
    </section>

    <h2>Le chaton réservé</h2>

    <table class="releve">
        <tr>
            <th>Nom</th>
            <td>
                {{ $chaton?->nom ?? '—' }}
                @if($chaton?->reference)<small>Référence d’élevage {{ $chaton->reference }}</small>@endif
            </td>
        </tr>
        <tr>
            <th>Race et robe</th>
            <td>Maine Coon{{ $chaton?->robe ? ' · '.$chaton->robe : '' }}</td>
        </tr>
        <tr>
            <th>Sexe</th>
            <td>{{ $chaton?->sexeLibelle() ?: '—' }}</td>
        </tr>
        <tr>
            <th>Né le</th>
            <td>{{ $portee?->date_naissance?->translatedFormat('j F Y') ?? '—' }}</td>
        </tr>
        <tr>
            <th>Identification</th>
            <td>
                {!! $chaton?->icad_numero
                    ? e($chaton->icad_numero)
                    : '<span class="a-completer">à compléter</span>' !!}
                <small>Puce électronique, enregistrée au fichier national ICAD</small>
            </td>
        </tr>
        <tr>
            <th>Numéro de portée LOOF</th>
            <td>{!! $portee?->loof_portee_numero
                ? e($portee->loof_portee_numero)
                : '<span class="a-completer">à compléter</span>' !!}</td>
        </tr>
        @if($portee?->pere || $portee?->mere)
            <tr>
                <th>Parents</th>
                <td>
                    {{ $portee?->pere?->nom ?? '—' }} × {{ $portee?->mere?->nom ?? '—' }}
                    <small>Dépistages consultables sur leur fiche, sur le site de l’élevage</small>
                </td>
            </tr>
        @endif
        <tr>
            <th>Départ prévu</th>
            <td>
                {{ $depart?->translatedFormat('j F Y') ?? 'à convenir' }}
                @if($portee?->dateCessionLegale())
                    <small>Pas avant le {{ $portee->dateCessionLegale()->translatedFormat('j F Y') }},
                    date des douze semaines révolues</small>
                @endif
            </td>
        </tr>
    </table>

    <h2>Le prix et l’acompte</h2>

    <table class="compte">
        <tr>
            <th>Désignation</th>
            <th class="somme">Montant</th>
        </tr>
        <tr>
            <td>
                Prix convenu du chaton
                @if($chaton?->nom)<small>{{ $chaton->nom }}, Maine Coon{{ $chaton->reference ? ' — '.$chaton->reference : '' }}</small>@endif
            </td>
            <td class="somme">{{ $reservation->prixFormate() }}</td>
        </tr>
        <tr>
            <td>
                Acompte à verser à la réservation
                @if($reservation->expire_le)
                    <small>avant le {{ $reservation->expire_le->translatedFormat('j F Y') }}</small>
                @endif
            </td>
            <td class="somme">− {{ $reservation->acompteFormate() }}</td>
        </tr>
        <tr class="total">
            <th>Solde à régler le jour du départ</th>
            <td class="somme">{{ $reservation->soldeFormate() }}</td>
        </tr>
    </table>

    @if(filled($clauses))
        <h2>Ce dont les parties conviennent</h2>
        <x-texte-riche :texte="$clauses" class="corps" />
    @endif

    @if(filled($conditions))
        <h2>Annexe — les conditions de l’acompte</h2>
        <x-texte-riche :texte="$conditions" class="annexe" />
    @endif

    <p class="lieu">
        Fait à {{ Setting::get('elevage.ville') }},
        le {{ $reservation->created_at?->translatedFormat('j F Y') }}, en deux exemplaires.
    </p>

    <section class="signatures">
        <div>
            <span class="etiquette">L’élevage</span>
            <div class="cadre"></div>
            <p class="mention">Signature</p>
        </div>
        <div>
            <span class="etiquette">La famille</span>
            <div class="cadre"></div>
            <p class="mention">Signature, précédée de la mention « lu et approuvé »</p>
        </div>
    </section>

@endsection

@section('pied')
    Contrat de réservation {{ $reservation->reference() }} ·
    {{ $chaton?->nom }} · {{ $reservation->nomComplet() }}
@endsection
