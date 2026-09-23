@props(['photos'])

@php
    /* Les fiches n'ont pas toutes plusieurs photos. Avec une seule, on rend
       exactement ce que rendait la fiche avant la visionneuse : un cadre, une
       image. Pas de ruban vide, pas de compteur « 1 / 1 ». */
    $photos = collect($photos)->values();
@endphp

@if($photos->count() < 2)

    @if($photos->isNotEmpty())
        <div class="arche">
            <i><u>
                <x-img :src="$photos[0]['chemin']" :alt="$photos[0]['alt']"
                       sizes="(max-width:980px) 90vw, 42vw"
                       :largeur="1200" :hauteur="1500" :urgent="true" />
            </u></i>
        </div>
    @endif

@else

    <div class="viseur" data-lightbox>
        {{-- La scène empile toutes les photos et n'en montre qu'une : le passage
             de l'une à l'autre est un fondu, sans recalcul de mise en page et
             sans image qui saute. --}}
        <div class="arche scene" data-zoom role="button" tabindex="0" aria-label="Agrandir la photo">
            <i><u>
                @foreach($photos as $i => $photo)
                    <x-img :src="$photo['chemin']" :alt="$photo['alt']"
                           sizes="(max-width:980px) 90vw, 42vw"
                           @class(['visible' => $i === 0]) :urgent="$i === 0" />
                @endforeach
            </u></i>
            <span class="compteur"><b>1</b>&thinsp;/&thinsp;{{ $photos->count() }}</span>
            <span class="loupe">Agrandir</span>
        </div>

        {{-- Les vignettes sont la liste de référence : la visionneuse comme la
             vue plein écran lisent leurs data-full. Un groupe de boutons, pas un
             tablist : il n'y a pas de panneau à contrôler, et annoncer des
             onglets inexistants égare un lecteur d'écran. --}}
        <div class="rail" role="group" aria-label="Photos de la fiche">
            @foreach($photos as $i => $photo)
                <button type="button" class="vignette"
                        @if($i === 0) aria-current="true" @endif
                        tabindex="{{ $i === 0 ? '0' : '-1' }}"
                        data-full="{{ asset($photo['chemin']) }}"
                        data-legende="{{ $photo['legende'] ?: $photo['alt'] }}">
                    <x-img :src="$photo['chemin']"
                           alt="Photo {{ $i + 1 }} — {{ $photo['alt'] }}"
                           sizes="74px" :largeur="150" :hauteur="150" />
                </button>
            @endforeach
        </div>
    </div>

@endif
