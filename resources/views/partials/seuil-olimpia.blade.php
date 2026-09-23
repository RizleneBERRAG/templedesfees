{{--
    Le seuil : Olimpia, avant d'entrer.

    Un voile plein écran posé par-dessus le site à l'arrivée. On la regarde, on
    lit deux phrases, et on entre. Rien d'autre n'est demandé.

    La composition est centrée et tout en courbes : un médaillon ovale plutôt
    qu'une arche, son nom en italique, une trace dorée dessous. L'arche et le
    filet droit faisaient temple — c'était juste pour sa page, trop sévère pour
    un seuil qu'on traverse.

    Trois façons d'en sortir, et c'est voulu : la croix, le bouton « Entrer sur
    le site », et la touche Échap. Aucune n'est cachée, aucune ne piège.

    « Ne plus afficher » est une case, pas un réglage enfoui : cochée, elle ne
    se représente jamais ; laissée vide, elle ne revient pas non plus avant la
    prochaine visite.

    Elle ne paraît ni sur la page d'Olimpia — on n'annonce pas à quelqu'un ce
    qu'il est déjà en train de lire — ni sur les pages de réservation : une
    famille en train de verser un acompte n'a pas à voir surgir autre chose.
--}}
@php
    use App\Models\Setting;

    $seuilTexte = Setting::get('hommage.seuil');
    $seuilDates = Setting::get('hommage.dates');
@endphp

@unless(request()->routeIs('hommage') || request()->routeIs('reservation.*'))
    <div class="seuil-olimpia" id="seuil-olimpia" hidden
         role="dialog" aria-modal="true" aria-labelledby="seuil-olimpia-nom">

        <div class="voile" aria-hidden="true"></div>

        <div class="feuille">

            {{-- La lueur chaude derrière le médaillon. C'est elle qui réchauffe
                 tout le panneau : sans elle, le fond reste une nuit froide. --}}
            <span class="lueur" aria-hidden="true"></span>

            <span class="maison">
                @if(file_exists(public_path('images/blason-192.png')))
                    <img src="{{ asset('images/blason-192.png') }}" alt="" width="28" height="28">
                @endif
                <b>Temple des Fées</b>
            </span>

            <button class="fermer" type="button" aria-label="Fermer et entrer sur le site">
                <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor"
                     stroke-width="1.3" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>

            <div class="dedans">

                {{-- Le médaillon. Un ovale, et une bordure faite d'un dégradé
                     d'or plutôt que d'un trait : une dorure a des reflets, un
                     filet n'en a pas. --}}
                <span class="medaillon">
                    <x-img src="images/hommage/olimpia.webp"
                           alt="Olimpia Maryliss Country, femelle Maine Coon blanche"
                           sizes="240px" :urgent="true" />
                </span>

                <span class="rubrique">En mémoire</span>

                <h2 class="nom" id="seuil-olimpia-nom">
                    <span class="sous-pinceau">Olimpia<x-pinceau /></span>
                </h2>

                <p class="complet">Olimpia Maryliss&nbsp;Country</p>

                @if($seuilDates)
                    <p class="dates">{{ $seuilDates }}</p>
                @endif

                @if(filled($seuilTexte))
                    <x-texte-riche class="mots" :texte="$seuilTexte" />
                @endif

                <div class="pied">
                    <button class="btn entrer" type="button">
                        Entrer sur le site
                        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor"
                             stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14m0 0-6-6m6 6-6 6"/>
                        </svg>
                    </button>

                    <div class="menus">
                        <a class="lire" href="{{ route('hommage') }}">Lire son histoire</a>
                        <span class="sep" aria-hidden="true">·</span>
                        <label class="plus">
                            <input type="checkbox" id="seuil-olimpia-jamais">
                            <span>Ne plus afficher</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endunless
