@extends('layouts.document')

{{--
    La facture d'acompte.

    Elle n'existe qu'une fois l'acompte encaissé — une facture d'acompte atteste
    un versement reçu, elle ne l'annonce pas. Son numéro est alloué à ce
    moment-là, dans une suite continue et sans trou.

    Son contenu vient de la fiche, jamais d'une saisie : le nom, le montant, la
    date, le chaton, le contrat auquel elle se rattache. Une facture qu'on
    retape est une facture qui finit par ne plus correspondre au contrat.
--}}

@section('titre', 'Facture d’acompte '.$reservation->facture_numero)
@section('genre', 'Facture d’acompte')
@section('numero', $reservation->facture_numero)
@section('date', $reservation->facture_le?->translatedFormat('j F Y'))

@php
    use App\Models\Setting;

    $tva       = Setting::get('legal.tva');
    $reglement = $reservation->stripe_payment_intent
        ? 'Carte bancaire'
        : 'Remis à l’élevage';

    /*
        La ligne de désignation s'assemble ici et non dans le gabarit : coller
        deux @if bout à bout ne marche pas — Blade ne reconnaît une directive
        que précédée d'un caractère non alphanumérique, et « @endif@if » laisse
        passer la seconde en texte brut.
    */
    $designation = collect([
        $chaton?->nom,
        $chaton?->reference ? 'référence '.$chaton->reference : null,
        $chaton?->icad_numero ? 'identification '.$chaton->icad_numero : null,
    ])->filter()->join(', ');

    // Ce qui restera dû, en une phrase, quand le prix est connu.
    $phraseSolde = $reservation->prix_centimes === null
        ? 'Le solde sera réglé le jour du départ.'
        : 'Le prix convenu est de '.$reservation->prixFormate().' ; il restera donc '
            .$reservation->soldeFormate().' à régler le jour du départ'
            .($reservation->departPrevu()
                ? ', prévu le '.$reservation->departPrevu()->translatedFormat('j F Y')
                : '').'.';
@endphp

@section('document')

    @if($reservation->factureEstFictive())
        {{-- Une facture de démonstration ne se confond avec rien : elle le dit
             en haut, elle porte un numéro d'une autre série, et elle ne touche
             pas à la numérotation de l'année. --}}
        <h2 class="alarme">Document de démonstration</h2>
        <p class="corps">
            Aucune somme n’a été prélevée et cette facture n’a aucune valeur
            comptable. Elle sert à montrer le document que recevra une famille
            une fois le compte de paiement ouvert.
        </p>
    @endif

    <section class="parties">
        <div>
            <span class="etiquette">Facturé à</span>
            <p class="nom">{{ $reservation->nomComplet() }}</p>
            <p class="fin">
                @if($reservation->adresse){{ $reservation->adresse }}<br>@endif
                @if($reservation->code_postal || $reservation->ville)
                    {{ $reservation->code_postal }} {{ $reservation->ville }}<br>
                @endif
                {{ $reservation->email }}
            </p>
        </div>

        <div>
            <span class="etiquette">Règlement</span>
            <p class="nom">{{ $reglement }}</p>
            <p class="fin">
                Reçu le {{ $reservation->paye_le?->translatedFormat('j F Y') }}<br>
                Se rattache au contrat de réservation {{ $reservation->reference() }}
            </p>
        </div>
    </section>

    <table class="compte">
        <tr>
            <th>Désignation</th>
            <th class="somme">Montant</th>
        </tr>
        <tr>
            <td>
                Acompte sur la réservation d’un chaton Maine Coon
                <small>{{ $designation }}</small>
            </td>
            <td class="somme">{{ $reservation->acompteFormate() }}</td>
        </tr>
        <tr class="total">
            <th>Total réglé</th>
            <td class="somme">{{ $reservation->acompteFormate() }}</td>
        </tr>
    </table>

    <p class="lieu">
        {!! $tva
            ? e($tva)
            : '<span class="a-completer">mention de TVA à compléter</span>' !!}
    </p>

    <span class="acquittee">Facture acquittée</span>

    <h2>Ce que cet acompte engage</h2>

    <div class="corps">
        <p>
            Cet acompte réserve le chaton désigné ci-dessus au nom de
            {{ $reservation->nomComplet() }}. Depuis son encaissement, le chaton
            est retiré de la vente et n’est proposé à personne d’autre.
        </p>
        <p>
            Il vient en déduction du prix du chaton : ce n’est pas un supplément.
            {{ $phraseSolde }}
        </p>
        <p>
            Les conditions complètes figurent en annexe du contrat de réservation
            {{ $reservation->reference() }}, signé entre les parties.
        </p>
    </div>

@endsection

@section('pied')
    Facture d’acompte {{ $reservation->facture_numero }} ·
    {{ $chaton?->nom }} · {{ $reservation->nomComplet() }}{{ $tva ? ' · '.$tva : '' }}
@endsection
