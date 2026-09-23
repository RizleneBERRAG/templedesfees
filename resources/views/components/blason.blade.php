@props(['taille' => 42, 'lien' => false])

{{--
    Le blason : la tête de Maine Coon du logo, reprise dans la dorure de la
    charte par scripts/blason.php.

    Tant que le fichier n'est pas là, le composant ne rend rien. Un cadre vide
    ou une image cassée dans le bandeau se voit bien plus qu'une absence, et
    le site doit rester présentable à toutes les étapes.
--}}
@php
    $fichier = public_path('images/blason.png');
@endphp

@if(file_exists($fichier))
    @if($lien)
        <a class="blason-lien" href="{{ route('home') }}" aria-label="Accueil — Chatterie du Temple des Fées">
            <img class="blason" src="{{ asset('images/blason.png') }}" alt=""
                 width="{{ $taille }}" height="{{ $taille }}" loading="lazy" decoding="async">
        </a>
    @else
        <img {{ $attributes->merge(['class' => 'blason']) }}
             src="{{ asset('images/blason.png') }}"
             alt="Chatterie du Temple des Fées"
             width="{{ $taille }}" height="{{ $taille }}" loading="lazy" decoding="async">
    @endif
@endif
