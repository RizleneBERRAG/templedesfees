@extends('layouts.app')

{{--
    En mémoire d'Olimpia Maryliss Country.

    Une page à elle seule. Pas une fiche de chat parmi les autres, pas une
    vignette dans une galerie : elle n'est plus de l'élevage, elle en est
    l'origine, et une page qui rend hommage ne se met pas en grille.

    Tout y est plus lent qu'ailleurs sur le site. Une seule colonne de texte,
    un seul portrait, aucun bouton qui appelle à autre chose. Le seul lien qui
    sort de la page mène à sa fille — c'est le seul endroit où elle continue.
--}}

@section('title', "En mémoire d'Olimpia")
@section('description', "Olimpia Maryliss Country. Elle a brillé sur les podiums, elle arrivait en courant à un seul mot, et elle a laissé une fille qui lui ressemble en tout.")
@section('og_image', asset('images/hommage/olimpia.webp'))

@section('content')

<section class="bande hommage">

    {{-- Le halo. Une seule lueur, très large et très lente, derrière le
         portrait : c'est la seule chose qui bouge sur cette page. --}}
    <div class="lueur" aria-hidden="true"></div>

    <div class="wrap">

        <div class="hommage-grille">

            <div class="hommage-portrait monte">
                <x-fleuron taille="petit" class="cimier" />

                <div class="cadre">
                    {{-- Le filet d'or se dessine à l'arrivée, d'un trait, du
                         bas vers le haut et jusqu'à la clef de l'arche. Il est
                         purement décoratif : aucune information n'en dépend. --}}
                    <svg class="trace-arche" viewBox="0 0 280 400" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M2 398 L2 140 A138 138 0 0 1 278 140 L278 398"
                              fill="none" stroke="currentColor" stroke-width="1.2"
                              vector-effect="non-scaling-stroke" />
                    </svg>

                    <div class="arche">
                        <i><u>
                            <x-img src="images/hommage/olimpia.webp"
                                   alt="Olimpia Maryliss Country, femelle Maine Coon blanche"
                                   sizes="(max-width:900px) 84vw, 38vw" :urgent="true" />
                        </u></i>
                    </div>
                </div>
            </div>

            <div class="hommage-texte">

                <p class="rubrique monte">En mémoire</p>

                {{-- Son nom, souligné à l'or. C'est le coup de pinceau de la
                     charte, posé là où il veut dire quelque chose. --}}
                <h1 class="nom monte">
                    <span class="sous-pinceau">Olimpia<x-pinceau /></span>
                </h1>

                <p class="complet monte">Olimpia Maryliss&nbsp;Country</p>

                @if($dates)
                    <p class="dates monte">{{ $dates }}</p>
                @endif

                @if(filled($texte))
                    <div class="recit monte">
                        <x-texte-riche :texte="$texte" />
                    </div>
                @endif

                {{-- Sa dernière phrase, et la seule qui soit signée. Elle est
                     le point final de la page : rien ne vient après. --}}
                <figure class="adieu monte">
                    <blockquote>Je ne l’oublierai jamais.</blockquote>
                    <figcaption>{{ \App\Models\Setting::get('legal.directeur', 'Son éleveur') }}</figcaption>
                </figure>

                @if($fille)
                    <div class="filiation monte">
                        <span class="etiquette">Elle continue</span>
                        <a href="{{ route('cats.show', $fille) }}">
                            @if($fille->photo_principale)
                                <span class="vignette">
                                    <x-img :src="$fille->photo_principale" :alt="$fille->nom" sizes="76px" />
                                </span>
                            @endif
                            <span class="dit">
                                <b>{{ $fille->nom }}</b>
                                <small>Sa fille, sur le site de l’élevage</small>
                            </span>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection
