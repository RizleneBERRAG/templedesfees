@extends('layouts.app')

@section('title', "Acompte reçu — {$chaton?->nom}")
@section('description', "Confirmation de réservation.")

@push('head')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')

@php
    use App\Enums\ReservationStatus;
    $recu = $reservation->statut === ReservationStatus::Payee;
@endphp

<section class="bande">
    <div class="wrap" style="max-width:780px">

        <div class="frise monte" style="margin-bottom:clamp(30px,4vw,50px)">
            <x-fleuron taille="petit" />
        </div>

        <div class="chapitre monte">
            <span class="rubrique">{{ $recu ? 'Acompte reçu' : 'Paiement en cours de confirmation' }}</span>
            <h1>{{ $chaton?->nom }} vous est réservé</h1>

            @if($recu)
                <p class="lede">
                    Merci. Votre acompte de {{ $reservation->acompteFormate() }} est arrivé, et
                    {{ $chaton?->nom }} n’est plus proposé à personne d’autre.
                </p>
            @else
                {{-- Le retour du navigateur peut precéder la notification de la
                     banque de quelques secondes. On ne ment pas : on dit que
                     c'est en cours, et la page se recharge d'elle-meme. --}}
                <p class="lede">
                    Votre paiement est en cours de confirmation par la banque. Cela prend
                    quelques secondes — cette page se met à jour toute seule.
                </p>
            @endif
        </div>

        @unless($recu)
            <meta http-equiv="refresh" content="6">
        @endunless

        <x-record titre="Ce qui se passe maintenant" meta="{{ $reservation->nomComplet() }}" class="monte">
            <table>
                <tr>
                    <th>Tout de suite</th>
                    <td>
                        <span class="verdict" style="font-size:16px">Un reçu par courriel</span>
                        <small>Envoyé à {{ $reservation->email }} par notre prestataire de paiement.</small>
                    </td>
                </tr>
                <tr>
                    <th>Chaque semaine</th>
                    <td>
                        <span class="verdict" style="font-size:16px">Des photos</span>
                        <small>Jusqu’au départ, pour que vous le voyiez grandir.</small>
                    </td>
                </tr>
                @if($chaton?->litter?->date_disponibilite)
                    <tr>
                        <th>Le {{ $chaton->litter->date_disponibilite->translatedFormat('j F Y') }}</th>
                        <td>
                            <span class="verdict" style="font-size:16px">Le départ</span>
                            <small>Identifié, primo-vacciné et rappelé, vermifugé, pedigree LOOF en main.
                            L’acompte est déduit du solde ce jour-là.</small>
                        </td>
                    </tr>
                @endif
            </table>
        </x-record>

        <div class="btnrow monte" style="justify-content:center;margin-top:clamp(28px,3.4vw,40px)">
            <a class="btn" href="{{ route('kittens.show', $chaton) }}">La fiche de {{ $chaton?->nom }}</a>
            <a class="btn creux" href="{{ route('contact') }}">Nous joindre</a>
        </div>
    </div>
</section>

@endsection
