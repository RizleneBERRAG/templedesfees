@extends('layouts.app')

@section('title', "Chatons Maine Coon disponibles")
@section('description', "Les chatons Maine Coon de la portée en cours : robe, sexe, poids, suivi vétérinaire et statut mis à jour. Inscrits au LOOF, cédés identifiés et vaccinés.")

@section('content')

{{--
    Une section par portee en cours.

    L'elevage peut avoir plusieurs portees la meme saison : n'en montrer qu'une
    rangeait les autres parmi les portees passees, alors que leurs chatons
    attendaient une famille. Le titre, les parents et le calendrier
    appartiennent a chaque portee, la barre de filtres reste commune.
--}}
<section class="bande">
    <div class="wrap">
        <div class="frise" style="margin-bottom:clamp(30px,4vw,50px)"><x-fleuron taille="grand" /></div>

        <x-section-head
            class="monte"
            niveau="1"
            eyebrow="{{ $portees->count() > 1 ? $portees->count().' portées' : $portees->first()->code }}"
            titre="{{ $portees->count() > 1 ? 'Les portées en cours' : 'La portée en cours' }}"
            lede="Identifiés, vaccinés, vermifugés et inscrits au LOOF. Aucun chaton ne part avant douze semaines." />

        <nav class="filtres monte" aria-label="Filtrer les chatons" data-filtre-barre>
            <a href="{{ route('kittens.index') }}" @if(! $statut) aria-current="true" @endif>Tous ({{ $total }})</a>
            @foreach(\App\Enums\KittenStatus::cases() as $cas)
                @if(($filtres[$cas->value] ?? 0) > 0)
                    <a href="{{ route('kittens.index', ['statut' => $cas->value]) }}"
                       @if($statut === $cas->value) aria-current="true" @endif>
                        {{ $cas->libelle() }}s ({{ $filtres[$cas->value] }})
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</section>

@foreach($portees as $portee)
<section class="bande @if(! $loop->first) creuse @endif">
    <div class="wrap">
        <x-section-head
            class="monte"
            niveau="2"
            eyebrow="{{ $portee->code }}"
            titre="{{ $portee->pere?->nom }} × {{ $portee->mere?->nom }}"
            lede="{{ $portee->date_naissance ? 'Nés le '.$portee->date_naissance->translatedFormat('j F Y').'. ' : '' }}{{ $portee->phraseDisponibilite() ? \Illuminate\Support\Str::ucfirst($portee->phraseDisponibilite()).'.' : '' }}" />

        @php($chatons = $visibles($portee))

        @forelse($chatons as $chaton)
            @if($loop->first)<div class="fiches monte" data-filtre="statut"
                 data-filtre-vide="Aucun chaton de cette portée dans cette catégorie.">@endif
                <x-kitten-card :chaton="$chaton" />
            @if($loop->last)</div>@endif
        @empty
            <p class="lede monte" style="text-align:center;margin-inline:auto">
                Aucun chaton de cette portée dans cette catégorie.
            </p>
        @endforelse

        @if($portee->events->isNotEmpty())
            <div class="duo-texte haut monte" style="margin-top:clamp(34px,4vw,52px)">
                <div class="pile">
                    <span class="rubrique">Suivi de la portée</span>
                    <h3>Où en sont-ils aujourd’hui</h3>
                    <p class="lede">
                        Le même calendrier pour les {{ $portee->nb_chatons }} chatons. Il se remplit
                        au fil des actes vétérinaires saisis dans l’espace de gestion — ce n’est pas
                        un texte écrit une fois pour toutes, c’est l’état réel de la portée.
                        Le jalon vert est l’âge légal de cession : douze semaines.
                    </p>
                </div>

                <x-record titre="Calendrier de la {{ \Illuminate\Support\Str::lower($portee->code) }}"
                          meta="{{ $portee->date_naissance?->translatedFormat('j F Y') ?? '' }}">
                    <div style="padding-top:18px"><x-timeline :events="$portee->events" /></div>
                </x-record>
            </div>
        @endif
    </div>
</section>
@endforeach

<div class="bande serree">
    <x-photo-strip titre="Les chatons au fil des semaines" />
</div>

@if($archives->isNotEmpty())
<section class="bande creuse">
    <div class="wrap">
        <x-section-head
            class="monte"
            eyebrow="Historique"
            titre="Les portées précédentes"
            lede="Elles restent en ligne. C’est la meilleure preuve du sérieux d’un élevage : on voit ce que sont devenus les chatons, et on voit combien sont revenus." />

        @foreach($archives as $archive)
            <div class="duo-texte monte" @if(! $loop->first) style="margin-top:clamp(34px,4vw,52px)" @endif>
                <figure class="vue">
                    <img src="{{ asset($archive->photo_principale ?: 'images/cats/portee-b.webp') }}"
                         alt="{{ $archive->code }}, chatons Maine Coon" loading="lazy">
                    <figcaption>{{ $archive->code }}{{ $archive->date_naissance ? ' — '.$archive->date_naissance->translatedFormat('F Y') : '' }}</figcaption>
                </figure>
                <div class="pile">
                    <p class="lede">{{ $archive->description }}</p>
                    <div class="faits">
                        <div class="fait"><b data-compte="{{ $archive->kittens_count }}">0</b><span>chatons</span></div>
                        <div class="fait"><b data-compte="{{ $archive->adoptes_count }}">0</b><span>adoptés</span></div>
                        <div class="fait"><b data-compte="0">0</b><span>retour à l’élevage</span></div>
                        <div class="fait"><b data-compte="{{ \App\Models\Litter::SEMAINES_AVANT_CESSION }}">0</b><span>semaines avant le départ</span></div>
                    </div>
                    <p class="petit">
                        Aucun nom de famille d’adoptant n’est publié sur ce site. Le statut d’un chaton
                        est une information sur le chaton, pas sur la personne qui l’a accueilli.
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

@endsection
