@extends('layouts.app')

@section('title', "Réservation de {$chaton?->nom}")
@section('description', "Page de réservation privée.")

@push('head')
    {{-- Une page privee, atteinte par un lien envoye a une famille precise :
         elle n'a rien a faire dans un moteur de recherche. --}}
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')

@php
    use App\Enums\ReservationStatus;
    use App\Services\Caisse;

    $etat = $reservation->statut;
    $perimee = $reservation->estPerimee();
@endphp

<section class="bande">
    <div class="wrap" style="max-width:940px">

        <div class="chapitre monte">
            <span class="rubrique">Réservation · {{ $reservation->nomComplet() }}</span>
            <h1>{{ $chaton?->nom ?? 'Chaton' }}</h1>
            <p class="lede">
                @if($etat === ReservationStatus::Payee)
                    L’acompte est arrivé. {{ $chaton?->nom }} vous est réservé.
                @elseif($perimee || $etat === ReservationStatus::Expiree)
                    Le délai de cette réservation est passé.
                @elseif($etat === ReservationStatus::Annulee)
                    Cette réservation a été annulée.
                @elseif($etat === ReservationStatus::Remboursee)
                    L’acompte a été remboursé.
                @else
                    Voici ce qui vous est réservé, et ce que couvre l’acompte.
                    Prenez le temps de lire avant de valider.
                @endif
            </p>
        </div>

        @if(Caisse::enDemonstration() && $reservation->peutEtrePayee())
            {{-- Ce bandeau n'apparait que tant qu'aucune clef Stripe n'est
                 renseignee. Il doit etre impossible de le confondre avec un
                 vrai paiement. --}}
            <p class="note-apercu monte" style="border-color:var(--alerte)">
                <span class="sceau" aria-hidden="true" style="border-color:var(--alerte);color:var(--alerte)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                    </svg>
                </span>
                <span>
                    <b>Démonstration — aucun paiement réel</b>
                    Le compte de paiement n’est pas encore ouvert. Le bouton ci-dessous
                    marque la réservation comme payée pour montrer le parcours complet.
                    Aucune carte n’est demandée, aucune somme n’est prélevée.
                </span>
            </p>
        @endif

        @if(session('erreur'))
            <p class="note-apercu monte">
                <span class="sceau" aria-hidden="true">!</span>
                <span>{{ session('erreur') }}</span>
            </p>
        @endif

        <div class="duo-texte haut monte" style="align-items:start">

            {{-- ── le chaton ── --}}
            <div class="pile">
                @if($chaton?->photo_principale)
                    <div class="arche">
                        <i><u>
                            <x-img :src="$chaton->photo_principale"
                                   :alt="$chaton->nom.', chaton Maine Coon'"
                                   sizes="(max-width:980px) 90vw, 42vw" :urgent="true" />
                        </u></i>
                    </div>
                @endif
            </div>

            {{-- ── ce qu'on paie ── --}}
            <div class="pile">
                <x-record titre="Le chaton" meta="{{ $chaton?->reference }}">
                    <table>
                        <tr><th>Nom</th><td>{{ $chaton?->nom }}</td></tr>
                        <tr><th>Sexe</th><td>{{ \Illuminate\Support\Str::ucfirst($chaton?->sexe ?? '—') }}</td></tr>
                        <tr><th>Robe</th><td>{{ $chaton?->robe }}</td></tr>
                        @if($chaton?->litter?->date_disponibilite)
                            <tr>
                                <th>Départ prévu</th>
                                <td>{{ $chaton->litter->date_disponibilite->translatedFormat('j F Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </x-record>

                <x-record titre="L’acompte" meta="{{ $etat->libelle() }}"
                          note="L’acompte est déduit du prix du chaton au moment du départ. Il n’est pas un supplément.">
                    <table>
                        <tr>
                            <th>Montant</th>
                            <td><span class="verdict">{{ $reservation->acompteFormate() }}</span></td>
                        </tr>
                        <tr>
                            <th>Ce qu’il engage</th>
                            <td>
                                <span class="verdict" style="font-size:16px">Le chaton vous est réservé</span>
                                <small>Il n’est plus proposé à personne d’autre, et vous recevez des
                                photos toutes les semaines jusqu’au départ.</small>
                            </td>
                        </tr>
                        @if($reservation->expire_le && $reservation->statut->attendUnPaiement())
                            <tr>
                                <th>À régler avant le</th>
                                <td>
                                    <span @class(['verdict', 'attente' => ! $perimee])>
                                        {{ $reservation->expire_le->translatedFormat('j F Y') }}
                                    </span>
                                    <small>Passé ce délai, le chaton est de nouveau proposé.</small>
                                </td>
                            </tr>
                        @endif
                        @if($reservation->paye_le)
                            <tr>
                                <th>Reçu le</th>
                                <td>{{ $reservation->paye_le->translatedFormat('j F Y à H\hi') }}</td>
                            </tr>
                        @endif
                    </table>
                </x-record>

                @if($reservation->peutEtrePayee())
                    <form method="POST" action="{{ route('reservation.payer', ['jeton' => $reservation->jeton]) }}"
                          class="demande">
                        @csrf

                        <label class="consent">
                            <input type="checkbox" name="conditions" value="1" required
                                   @checked(old('conditions'))>
                            <span>
                                J’ai lu et j’accepte les conditions de l’acompte : il réserve le chaton
                                à mon nom, il est déduit du prix au départ, et il reste acquis à
                                l’élevage si je renonce sans motif.
                                <a href="{{ route('legal') }}">Mentions légales</a>
                            </span>
                        </label>

                        @error('conditions')
                            <p class="erreur">{{ $message }}</p>
                        @enderror

                        <div class="btnrow" style="margin-top:6px">
                            <button class="btn" type="submit">
                                @if(Caisse::enDemonstration())
                                    Simuler le versement de {{ $reservation->acompteFormate() }}
                                @else
                                    Verser l’acompte de {{ $reservation->acompteFormate() }}
                                @endif
                            </button>
                        </div>

                        @unless(Caisse::enDemonstration())
                            <p class="petit" style="margin-top:4px">
                                Paiement par carte, traité par Stripe. Aucun numéro de carte
                                ne transite par ce site ni n’y est conservé.
                            </p>
                        @endunless
                    </form>
                @elseif($etat === ReservationStatus::Payee)
                    <div class="btnrow">
                        <a class="btn creux" href="{{ route('kittens.show', $chaton) }}">Revoir la fiche de {{ $chaton?->nom }}</a>
                        <a class="btn creux" href="{{ route('contact') }}">Nous écrire</a>
                    </div>
                @else
                    <p class="petit">
                        Si vous pensez que c’est une erreur, appelez-nous : nous reprendrons
                        la réservation ensemble.
                    </p>
                    <div class="btnrow">
                        <a class="btn creux" href="{{ route('contact') }}">Nous joindre</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
