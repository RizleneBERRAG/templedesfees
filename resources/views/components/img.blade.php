@props([
    'src',
    'alt'     => '',
    'sizes'   => '100vw',
    'largeur' => null,
    'hauteur' => null,
    'urgent'  => false,
    'differe' => false,
])

{{--
    Une image qui se sert à la bonne taille.

    scripts/vignettes.php pose deux réductions à côté de chaque photo, en 800
    et en 400 px. Ce composant les déclare si elles existent : le navigateur
    choisit alors ce qu'il lui faut selon la largeur d'affichage ET la densité
    de l'écran. Un écran Retina de 400 px prendra la version 800, un écran
    ordinaire la version 400.

    L'attribut sizes est obligatoire pour que ce choix soit juste : sans lui,
    le navigateur suppose que l'image fait toute la largeur de la fenêtre et
    prend systématiquement la plus grande. C'est à l'appelant de dire quelle
    place l'image occupe réellement.

    Quand les réductions n'existent pas — une photo qu'on vient de déposer —
    le composant sert simplement l'original. Rien ne casse.

    differe : l'adresse part en data-src plutôt qu'en src, et rien n'est
    téléchargé tant que du script ne l'a pas demandé. C'est pour les images
    qui sont DANS l'écran mais qu'on ne regarde pas encore — le fondu du seuil
    d'Olimpia. loading="lazy" n'y suffit pas : il ne diffère que ce qui est
    hors de l'écran, et ces images-là y sont déjà, simplement transparentes.
--}}
@php
    $base = preg_replace('/\.webp$/', '', $src);

    $srcset = collect([400, 800])
        ->filter(fn ($t) => file_exists(public_path("$base-$t.webp")))
        ->map(fn ($t) => asset("$base-$t.webp").' '.$t.'w');

    if ($srcset->isNotEmpty() && file_exists(public_path($src))) {
        $taille = getimagesize(public_path($src));
        $srcset->push(asset($src).' '.$taille[0].'w');
    }
@endphp

<img {{ $attributes }}
     @if($differe)
         data-src="{{ asset($src) }}"
         @if($srcset->isNotEmpty())
             data-srcset="{{ $srcset->implode(', ') }}"
             data-sizes="{{ $sizes }}"
         @endif
     @else
         src="{{ asset($src) }}"
         @if($srcset->isNotEmpty())
             srcset="{{ $srcset->implode(', ') }}"
             sizes="{{ $sizes }}"
         @endif
     @endif
     alt="{{ $alt }}"
     @if($largeur) width="{{ $largeur }}" @endif
     @if($hauteur) height="{{ $hauteur }}" @endif
     loading="{{ $urgent ? 'eager' : 'lazy' }}"
     @if($urgent) fetchpriority="high" @endif
     decoding="async">
