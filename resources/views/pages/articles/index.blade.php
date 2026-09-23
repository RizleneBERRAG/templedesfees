@extends('layouts.app')

@section('title', "Articles — conseils et vie de l'élevage")
@section('description', "Les articles de la Chatterie du Temple des Fées : soins du Maine Coon, alimentation, santé, préparation de l'arrivée d'un chaton et nouvelles de l'élevage.")

@section('content')

<section class="bande">
    <div class="wrap">
        <div class="frise" style="margin-bottom:clamp(30px,4vw,50px)"><x-fleuron taille="grand" /></div>

        <x-section-head
            class="monte"
            niveau="1"
            eyebrow="Articles"
            titre="Ce qu’on a appris, et qu’on écrit"
            lede="Des textes courts sur la race, ses soins et la vie de l’élevage. Pas de remplissage : on écrit quand on a quelque chose à dire." />

        @if($categories->isNotEmpty())
            <nav class="filtres monte" aria-label="Filtrer les articles">
                <a href="{{ route('articles.index') }}" @if(! $categorie) aria-current="true" @endif>Tous ({{ $total }})</a>
                @foreach($categories as $cat)
                    <a href="{{ route('articles.index', ['categorie' => $cat]) }}"
                       @if($categorie === $cat) aria-current="true" @endif>
                        {{ \Illuminate\Support\Str::ucfirst($cat) }} ({{ $compte[$cat] ?? 0 }})
                    </a>
                @endforeach
            </nav>
        @endif

        @forelse($articles as $article)
            @if($loop->first)<div class="articles monte">@endif

                <article class="billet">
                    <a class="arche petite" href="{{ route('articles.show', $article) }}" tabindex="-1" aria-hidden="true">
                        <i><u>
                            <x-img :src="$article->photo_principale ?: 'images/cats/uriana.webp'"
                                   alt="" sizes="200px" :largeur="1200" :hauteur="1714" />
                        </u></i>
                    </a>
                    <div class="bd">
                        @if($article->categorie)
                            <span class="rubrique">{{ $article->categorie }}</span>
                        @endif
                        <h2><a href="{{ route('articles.show', $article) }}">{{ $article->titre }}</a></h2>
                        <p>{{ $article->chapeau }}</p>
                        <span class="signe">
                            {{ $article->date_publication->translatedFormat('j F Y') }}
                            · {{ $article->minutesDeLecture() }} min de lecture
                        </span>
                    </div>
                </article>

            @if($loop->last)</div>@endif
        @empty
            {{-- Une rubrique vide est pire que pas de rubrique : au moins,
                 celle-ci dit ce qu'elle attend. --}}
            <div class="registre monte" style="max-width:640px;margin-inline:auto;text-align:center">
                <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>
                <span class="eq" aria-hidden="true"></span><span class="eq" aria-hidden="true"></span>
                <p class="lede" style="margin-inline:auto">
                    Le premier article est en cours d’écriture. En attendant, la page
                    <a class="lien" href="{{ route('breed') }}" style="margin-inline:6px">Le Maine Coon</a>
                    répond à l’essentiel.
                </p>
            </div>
        @endforelse
    </div>
</section>

@endsection
