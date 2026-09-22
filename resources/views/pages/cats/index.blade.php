@extends('layouts.app')

@section('title', "L'élevage et nos reproducteurs")
@section('description', "Chatterie du Temple des Fées, élevage familial déclaré à la chambre d'agriculture à Lapeyrouse-Mornay. Nos reproducteurs, leur robe, leur pedigree et leurs dépistages.")

@section('content')

<section class="band">
    <div class="wrap">
        <x-section-head
            niveau="1"
            eyebrow="L'élevage"
            titre="Chatterie du Temple des Fées"
            lede="Un élevage familial déclaré à la chambre d'agriculture, titulaire du certificat de capacité, installé à Lapeyrouse-Mornay, à vingt minutes de Lyon." />

        <div class="two off">
            <figure class="figure">
                <img src="{{ asset('images/cats/couple.webp') }}" alt="Deux Maine Coon de la chatterie">
                <figcaption>Uzumaki et Uanna</figcaption>
            </figure>
            <div class="stack">
                <h3>Deux adultes, une portée ou deux par an</h3>
                <p class="lede">
                    Nous ne faisons pas de volume. Le rythme des portées est calé sur la récupération
                    des femelles, jamais sur la demande. Entre deux portées, la maison redevient une
                    maison : quatre chats, deux enfants, un chien.
                </p>
                <p class="lede">
                    C'est ce qui explique que nos chatons arrivent chez vous déjà propres, habitués aux
                    bruits, et demandeurs de contact — ils n'ont jamais connu autre chose.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="band ink2">
    <div class="wrap">
        <x-section-head eyebrow="La lignée" titre="Nos reproducteurs"
            lede="Chaque chat a sa fiche complète : robe, pedigree, identification et résultats de dépistage." />
        <div class="repros">
            @foreach($chats as $chat)
                <x-cat-card :chat="$chat" />
            @endforeach
        </div>
    </div>
</section>

<x-photo-band image="images/cats/wild.webp"
              legende="Une à deux portées par an, pas davantage"
              hauteur="44vh" />

<section class="band paper">
    <div class="wrap">
        <x-section-head eyebrow="En clair" titre="L'élevage en quatre chiffres" />
        <div class="facts">
            <div class="fact"><b data-count="2">0</b><span>Portées par an maximum</span></div>
            <div class="fact"><b data-count="{{ \App\Models\Litter::SEMAINES_AVANT_CESSION }}">0</b><span>Semaines minimum avant départ</span></div>
            <div class="fact"><b data-count="2">0</b><span>Visites avant réservation</span></div>
            <div class="fact"><b data-count="4">0</b><span>Dépistages par reproducteur</span></div>
        </div>
        <div class="btnrow" style="margin-top:36px">
            <a class="btn" href="{{ route('kittens.index') }}">Voir les chatons</a>
            <a class="btn ghost" href="{{ route('contact') }}">Venir nous voir</a>
        </div>
    </div>
</section>

@endsection
