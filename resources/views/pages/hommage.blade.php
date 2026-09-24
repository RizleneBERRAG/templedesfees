@extends('layouts.app')

{{--
    En mémoire d'Olimpia Maryliss Country.

    Ce n'est pas une page, c'est un parcours. Sept moments, un par écran, qu'on
    traverse en descendant — on ne peut pas les lire vite, et c'est le but :
    ce qui touche, c'est le temps qu'on y passe, pas la quantité qu'on y met.

        1. elle, en plein écran, et son nom
        2. LE MOT — celui qui la faisait accourir, seul, en or
        3. ce qu'elle était, dans les mots de l'éleveur
        4. le départ, presque rien à l'écran
        5. sa fille — le seul moment où la page remonte
        6. la phrase signée
        7. ses tirages

    L'ordre n'est pas décoratif. On la regarde, on sourit du mot, on est fier
    d'elle, on la perd, et on découvre qu'il reste quelqu'un. Le soulagement du
    cinquième moment ne vaut que parce que le quatrième a dit la perte sans
    rien adoucir.

    Tout ce qui s'y lit vient de ce que l'éleveur a écrit lui-même, et se
    reprend depuis Le site › Réglages. Deux choses manquent tant qu'il ne les
    aura pas données : ses années, et le nom de sa fille. Les moments qui en
    dépendent n'existent pas sans elles — on n'affiche pas un trou.
--}}

@section('title', "En mémoire d'Olimpia")
@section('description', "Olimpia Maryliss Country. Il suffisait d'un mot pour qu'elle arrive en courant.")
@section('og_image', asset(\App\Support\PhotosOlimpia::principale() ?? 'images/cats/karrington.webp'))

@php
    // Les autres photos, quand il y en a : la deuxième pour l'arche, la
    // troisième pour la phrase signée. À défaut, la principale revient.
    $second = $suivantes[0] ?? $principale;
    $tiers  = $suivantes[1] ?? $principale;
@endphp

@section('content')

{{-- ═══ 1 · elle ═══
     Le portrait tient tout l'écran. Pas de titre, pas de bouton, rien à lire :
     on la regarde, on descend quand on veut. --}}
<section class="olimpia-ouverture">

    <x-img class="fond" :src="$principale" alt="" sizes="100vw" :urgent="true" />

    <div class="voile" aria-hidden="true"></div>

    <div class="dit">
        <span class="rubrique">En mémoire</span>
        <h1>Olimpia</h1>
        <p class="complet">Olimpia Maryliss&nbsp;Country</p>
        @if($dates)
            <p class="dates">{{ $dates }}</p>
        @endif
    </div>

    <a class="descendre" href="#le-mot">
        <span class="mot">Son histoire</span>
        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor"
             stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14m0 0-6-6m6 6 6-6"/>
        </svg>
    </a>
</section>

{{-- ═══ 2 · le mot ═══
     Un seul mot sur un écran entier. C'est le plus petit détail de toute son
     histoire, et c'est celui qui la contient. --}}
@if(filled($mot))
    <section class="moment moment-mot" id="le-mot">
        <div class="wrap">
            <p class="avant monte">Il suffisait d’un mot.</p>
            <p class="le-mot monte">«&nbsp;{{ $mot }}&nbsp;»</p>
            <p class="apres monte">Et elle arrivait en courant.</p>
        </div>
    </section>
@endif

{{-- ═══ 3 · ce qu'elle était ═══ --}}
<section class="moment moment-elle">
    <div class="wrap">
        <div class="duo">
            <div class="portrait monte">
                <div class="arche">
                    <i><u>
                        <x-img :src="$second"
                               alt="Olimpia Maryliss Country, femelle Maine Coon blanche"
                               sizes="(max-width:900px) 78vw, 36vw" />
                    </u></i>
                </div>
            </div>

            <div class="texte">
                <h2 class="monte">
                    <span class="sous-pinceau">Ce qu’elle était<x-pinceau /></span>
                </h2>

                @if(filled($texte))
                    <div class="recit monte">
                        <x-texte-riche :texte="$texte" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══ 4 · le départ ═══
     Presque rien à l'écran. C'est le seul endroit du site où le vide est
     l'information. --}}
<section class="moment moment-depart">
    <div class="wrap">
        <p class="phrase monte">Elle est partie trop tôt.</p>

        @if($dates)
            <p class="annees monte">{{ $dates }}</p>
        @endif

        <x-fleuron taille="petit" class="sceau monte" />
    </div>
</section>

{{-- ═══ 5 · sa fille ═══
     Le seul moment où la page remonte. --}}
@if($fille)
    <section class="moment moment-fille">
        <div class="wrap">
            <span class="rubrique monte">Et pourtant</span>

            <p class="phrase monte">
                Avant de partir, elle a laissé une fille
                qui lui ressemble en tout.
            </p>

            <a class="elle monte" href="{{ route('cats.show', $fille) }}">
                @if($fille->photo_principale)
                    <span class="vignette">
                        <x-img :src="$fille->photo_principale" :alt="$fille->nom" sizes="200px" />
                    </span>
                @endif
                <span class="dit">
                    <b>{{ $fille->nom }}</b>
                    <small>Sa fille, ici même</small>
                </span>
            </a>
        </div>
    </section>
@endif

{{-- ═══ 6 · la phrase ═══
     La seule qui soit signée. Elle est le point final : rien ne vient après
     que ses photos. --}}
@if(filled($adieu))
    <section class="moment moment-adieu">
        <x-img class="fond" :src="$tiers" alt="" sizes="100vw" />
        <div class="voile" aria-hidden="true"></div>

        <div class="wrap">
            <figure class="monte">
                <blockquote>{{ $adieu }}</blockquote>
                <figcaption>{{ \App\Models\Setting::get('legal.directeur', 'Son éleveur') }}</figcaption>
            </figure>
        </div>
    </section>
@endif

{{-- ═══ 7 · encore elle ═══
     Après la phrase signée, on la revoit. Des tirages posés sur une table,
     légèrement de travers — c'est un album de famille, pas une grille. --}}
@if($suivantes)
    <section class="moment moment-planche">
        <div class="wrap">
            <span class="rubrique monte">Encore elle</span>

            <div class="planche-olimpia monte" data-lightbox>
                @foreach($suivantes as $photo)
                    <figure data-full="{{ asset($photo) }}" data-legende="Olimpia">
                        <x-img :src="$photo" alt="Olimpia" sizes="220px" />
                    </figure>
                @endforeach
            </div>

            <p class="souffle monte">Cliquez pour les voir en grand.</p>
        </div>
    </section>
@endif

@endsection
