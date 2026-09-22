@extends('layouts.app')

@section('title', "Galerie — la vie à l'élevage")
@section('description', "Photos des Maine Coon de l'élevage : chatons, adultes et vie quotidienne à la maison, prises au fil des mois.")

@section('content')

<section class="band">
    <div class="wrap">
        <x-section-head
            niveau="1"
            eyebrow="Galerie"
            titre="La vie à l'élevage"
            lede="Des photos prises au fil des mois, pas une séance shooting. Cliquez pour agrandir." />

        @if($categories->isNotEmpty())
            <div class="filters">
                <a href="{{ route('gallery') }}" role="button"
                   aria-pressed="{{ request('categorie') ? 'false' : 'true' }}">Tout ({{ $photos->count() }})</a>
                @foreach($categories as $categorie)
                    <a href="{{ route('gallery', ['categorie' => $categorie]) }}" role="button"
                       aria-pressed="{{ request('categorie') === $categorie ? 'true' : 'false' }}">
                        {{ \Illuminate\Support\Str::ucfirst($categorie) }} ({{ $photos->where('categorie', $categorie)->count() }})
                    </a>
                @endforeach
            </div>
        @endif

        <div class="masonry" id="mas" data-lightbox>
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
