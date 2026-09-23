{{--
    La bannière d'Olimpia.

    Elle se présente une fois, à l'arrivée sur le site, et se retire d'un geste.
    Une fois retirée, elle ne revient plus : le navigateur s'en souvient. On ne
    fait pas redemander à quelqu'un la même attention deux fois.

    Ce n'est pas une fenêtre modale : rien n'est bloqué derrière, la page se lit
    et se parcourt normalement pendant qu'elle est là. Un hommage n'a pas à
    prendre le visiteur en otage.

    Elle ne paraît pas sur sa propre page — on n'annonce pas à quelqu'un ce
    qu'il est déjà en train de lire. Ni sur les pages de réservation : une
    famille en train de verser un acompte n'a pas à voir surgir autre chose.
--}}
@unless(request()->routeIs('hommage') || request()->routeIs('reservation.*'))
    <aside class="banniere-olimpia" id="banniere-olimpia" hidden
           aria-label="En mémoire d’Olimpia">

        <a class="corps" href="{{ route('hommage') }}">
            <span class="portrait">
                <x-img src="images/hommage/olimpia.webp" alt="" sizes="72px" />
            </span>

            <span class="dit">
                <span class="rubrique">En mémoire</span>
                <b>Olimpia</b>
                <span class="phrase">
                    Elle a brillé sur les podiums, et arrivait en courant à un seul mot.
                </span>
            </span>

            <span class="lien">
                Son histoire
                <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor"
                     stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14m0 0-6-6m6 6-6 6"/>
                </svg>
            </span>
        </a>

        <button class="fermer" type="button" aria-label="Retirer cette bannière">
            <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor"
                 stroke-width="1.3" stroke-linecap="round">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </aside>
@endunless
