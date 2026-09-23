@props(['pages', 'legende' => null])

@php
    /*
        Un feuillet porte deux pages : son recto, puis son verso une fois
        tourné. C'est ce qui fait qu'au premier coup d'œil on voit la page 1
        seule à droite, comme dans un vrai livre qu'on vient d'ouvrir, puis
        02-03, puis 04-05.

        Une page impaire en fin de livre laisse un verso vide : il se dessine
        alors comme une page blanche, ce qui est exactement ce qu'on trouve à
        la fin d'un ouvrage.
    */
    $feuillets = collect($pages)->chunk(2)->values();
    $romain = fn (int $n) => ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][$n - 1] ?? (string) $n;
@endphp

<div class="livre-bloc" data-livre>
    <figure class="livre" role="group" aria-roledescription="livre"
            aria-label="{{ $legende ?? 'Le livre de la maison' }}">

        {{-- Les plats : ce qu'on voit à gauche avant d'avoir tourné la
             première page, et à droite une fois le livre fini. --}}
        <span class="plat" aria-hidden="true"><i class="dos"></i></span>

        <div class="pages">
            @foreach($feuillets as $i => $duo)
                @php $duo = $duo->values(); @endphp
                <div class="feuillet" style="--i:{{ $i }}">
                    @for($j = 0; $j < 2; $j++)
                        @php $page = $duo[$j] ?? null; $numero = $i * 2 + $j + 1; @endphp
                        <article @class(['face', 'recto' => $j === 0, 'verso' => $j === 1, 'blanche' => ! $page])>
                            @if($page)
                                <span class="folio">{{ str_pad((string) $numero, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3>{{ $page['titre'] }}</h3>
                                <span class="filet" aria-hidden="true"></span>
                                <p>{{ $page['texte'] }}</p>
                                @if(! empty($page['chapitre']))
                                    <span class="chapitre-nom">Chapitre {{ $romain($numero) }}</span>
                                @endif
                            @else
                                <x-fleuron taille="petit" style="color:var(--or-ombre);margin:auto" />
                            @endif
                        </article>
                    @endfor
                </div>
            @endforeach
        </div>

        <span class="signet" aria-hidden="true"></span>
    </figure>

    <nav class="livre-barre" aria-label="Pages du livre">
        <button class="feuillet-bouton" type="button" data-pas="-1" aria-label="Page précédente">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 5 8 12l7 7"/>
            </svg>
        </button>

        <span class="livre-ou" aria-live="polite"></span>

        <button class="feuillet-bouton" type="button" data-pas="1" aria-label="Page suivante">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 5 7 7-7 7"/>
            </svg>
        </button>
    </nav>
</div>
