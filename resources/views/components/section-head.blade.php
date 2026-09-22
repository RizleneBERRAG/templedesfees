@props(['eyebrow' => null, 'titre', 'lede' => null, 'centre' => false, 'niveau' => 2])

{{--
    niveau="1" sur le titre principal d'une page, 2 pour les sections suivantes.
    Une page sans h1 ne dit pas a Google de quoi elle parle. L'echelle visuelle
    ne change pas : app.css ramene .shead h1 a la taille d'un h2, parce que la
    hierarchie de la maquette ne doit pas dependre du niveau de balise.
--}}

<div class="shead @if($centre) center @endif">
    @unless($centre)
        <x-rosettes class="rosettes rail" />
    @endunless
    <div class="txt">
        @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
        <h{{ $niveau }}>{!! $titre !!}</h{{ $niveau }}>
        @if($lede)<p class="lede">{!! $lede !!}</p>@endif
    </div>
</div>
