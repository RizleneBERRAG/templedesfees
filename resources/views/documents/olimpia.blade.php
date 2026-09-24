@extends('layouts.document')

{{--
    Le souvenir d'Olimpia — une feuille à imprimer.

    Tout le reste du site s'adresse à des visiteurs. Celle-ci s'adresse à une
    seule personne, et elle n'est pas faite pour être regardée à l'écran :
    elle est faite pour sortir d'une imprimante, entrer dans un cadre, et
    rester au mur.

    D'où le papier plutôt que la nuit. La charte du site est sombre, et c'est
    juste pour un écran — un A4 de noir vide une cartouche, sort gris sale sur
    une imprimante de maison, et ne s'encadre pas. Ivoire, encre sombre, un
    filet d'or : c'est le vocabulaire d'un faire-part, et c'en est un.

    Le mot est au centre, en grand. Sur la feuille comme sur sa page, c'est
    lui qui porte tout.
--}}

@section('titre', 'En mémoire d’Olimpia')
@section('genre', 'En mémoire')
@section('numero', 'Olimpia')
@section('date', $dates ?? '')

@section('document')

    <div class="souvenir">

        @if($portrait)
            <div class="portrait">
                <x-img :src="$portrait" alt="Olimpia Maryliss Country" sizes="420px" :urgent="true" />
            </div>
        @endif

        <p class="complet">Olimpia Maryliss&nbsp;Country</p>

        @if(filled($mot))
            <div class="le-mot">
                <p class="avant">Il suffisait d’un mot.</p>
                <p class="mot">«&nbsp;{{ $mot }}&nbsp;»</p>
                <p class="apres">Et elle arrivait en courant.</p>
            </div>
        @endif

        @if(filled($texte))
            <div class="recit">
                <x-texte-riche :texte="$texte" />
            </div>
        @endif

        @if(filled($adieu))
            <figure class="adieu">
                <blockquote>{{ $adieu }}</blockquote>
                <figcaption>{{ \App\Models\Setting::get('legal.directeur', 'Son éleveur') }}</figcaption>
            </figure>
        @endif
    </div>

@endsection
