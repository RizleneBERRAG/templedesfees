@props([
    'eyebrow' => null,
    'numero'  => null,
    'titre',
    'lede'    => null,
    'gauche'  => false,
    'niveau'  => 2,
])

{{--
    L'en-tête d'un chapitre.

    niveau="1" sur le titre principal d'une page, 2 pour les sections
    suivantes : une page sans h1 ne dit pas à Google de quoi elle parle.
    L'échelle visuelle ne change pas — app.css ramène .chapitre h1 à la taille
    d'un h2, parce que la hiérarchie de la charte ne doit pas dépendre du
    niveau de balise.

    numero porte le fil du récit (« Chapitre premier ») ; eyebrow sert quand la
    section n'est pas un chapitre mais une simple rubrique.
--}}

<div {{ $attributes->class(['chapitre', 'gauche' => $gauche]) }}>
    @if($numero)
        <span class="numero">{{ $numero }}</span>
    @elseif($eyebrow)
        <span class="rubrique">{{ $eyebrow }}</span>
    @endif

    <h{{ $niveau }}>{!! $titre !!}</h{{ $niveau }}>

    @if($lede)
        <p class="lede">{!! $lede !!}</p>
    @endif
</div>
