{{--
    Une liasse : plusieurs registres empilés dans le même cadre, dont un seul
    est à l'écran. On passe de l'un à l'autre par les deux flèches, par les
    jalons, au clavier (← →) ou d'un glissé du pouce.

    Sans JavaScript, rien ne se passe : la barre reste masquée et les fiches
    s'affichent les unes sous les autres, exactement comme avant. C'est le
    script qui retire le « hidden » et prend la main.

    Les jalons ne sont pas écrits ici : le script lit le titre de chaque
    registre. Un seul endroit où le nom d'une fiche est écrit, donc aucun
    risque de le voir diverger.
--}}
<div {{ $attributes->merge(['class' => 'liasse']) }} data-liasse>
    <div class="liasse-scene">{{ $slot }}</div>

    <nav class="liasse-barre" aria-label="Fiches de ce chat" hidden>
        <button class="feuillet-bouton" type="button" data-pas="-1" aria-label="Fiche précédente">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 5 8 12l7 7"/>
            </svg>
        </button>

        <ol class="jalons"></ol>

        <button class="feuillet-bouton" type="button" data-pas="1" aria-label="Fiche suivante">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 5 7 7-7 7"/>
            </svg>
        </button>
    </nav>
</div>
