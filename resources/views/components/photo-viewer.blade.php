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
                <img src="{{ asset($photos[0]['chemin']) }}" alt="{{ $photos[0]['alt'] }}"
                     width="1200" height="1500">
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
                    <img src="{{ asset($photo['chemin']) }}" alt="{{ $photo['alt'] }}"
                         @class(['visible' => $i === 0])
                         @if($i > 0) loading="lazy" @endif>
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
                    <img src="{{ asset($photo['chemin']) }}"
                         alt="Photo {{ $i + 1 }} — {{ $photo['alt'] }}"
                         loading="lazy" width="150" height="150">
                </button>
            @endforeach
        </div>
    </div>

@endif
