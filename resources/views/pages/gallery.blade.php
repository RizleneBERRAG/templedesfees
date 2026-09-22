@extends('layouts.app')

@section('title', "Galerie — la vie à l'élevage")
@section('description', "Photos des Maine Coon de l'élevage : chatons, adultes et vie quotidienne à la maison, prises au fil des mois.")

@section('content')

<section class="bande">
    <div class="wrap">
        <x-section-head
            class="monte"
            niveau="1"
            eyebrow="Galerie"
            titre="La vie à l'élevage"
            lede="Des photos prises au fil des mois, pas une séance shooting. Cliquez pour agrandir." />

        @if($categories->isNotEmpty())
            <nav class="filtres monte" aria-label="Filtrer les photos">
                <a href="{{ route('gallery') }}"
                   @if(! request('categorie')) aria-current="true" @endif>Tout ({{ $photos->count() }})</a>
                @foreach($categories as $categorie)
                    <a href="{{ route('gallery', ['categorie' => $categorie]) }}"
                       @if(request('categorie') === $categorie) aria-current="true" @endif>
                        {{ \Illuminate\Support\Str::ucfirst($categorie) }} ({{ $photos->where('categorie', $categorie)->count() }})
                    </a>
                @endforeach
            </nav>
        @endif

        <div class="mosaique monte" id="mas" data-lightbox>
            @foreach($photos->when(request('categorie'), fn ($c) => $c->where('categorie', request('categorie'))) as $photo)
                <figure data-full="{{ asset($photo->chemin) }}" data-legende="{{ $photo->legende }}">
                    <img src="{{ asset($photo->chemin) }}" alt="{{ $photo->alt }}" loading="lazy">
                    <figcaption>{{ $photo->legende }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

@endsection
