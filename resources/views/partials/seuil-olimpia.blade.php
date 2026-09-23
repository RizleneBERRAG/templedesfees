{{--
    Le seuil : Olimpia, avant d'entrer.

    Un voile plein écran posé par-dessus le site à l'arrivée. On la regarde, on
    lit deux phrases, et on entre.

    TROIS FORMES, le temps de choisir :

        ?seuil=a   le faire-part   — une carte d'ivoire posée sur la nuit
        ?seuil=b   le plein cadre  — sa photo prend tout l'écran, rien autour
        ?seuil=c   le médaillon    — le panneau sombre, ovale doré (en place)

    Le jour du choix, les deux perdantes disparaissent avec ce commutateur : il
    n'y a aucune raison de garder trois chemins pour un seul seuil.

    Trois façons d'en sortir dans tous les cas, et c'est voulu : la croix, le
    bouton « Entrer sur le site », et la touche Échap. Aucune n'est cachée.

    Elle ne paraît ni sur la page d'Olimpia — on n'annonce pas à quelqu'un ce
    qu'il est déjà en train de lire — ni sur les pages de réservation : une
    famille en train de verser un acompte n'a pas à voir surgir autre chose.
--}}
@php
    use App\Models\Setting;
    use App\Support\PhotosOlimpia;

    $seuilTexte = Setting::get('hommage.seuil');
    $seuilDates = Setting::get('hommage.dates');

    // Toutes passent au seuil, y compris les deux plus petites : c'est un
    // choix assumé. Étalées, elles sont moins nettes que les autres — le
    // grain et le voile en rattrapent une bonne part.
    $photos     = PhotosOlimpia::toutes();
    $principale = PhotosOlimpia::principale();

    $forme = in_array(request('seuil'), ['a', 'b', 'c'], true) ? request('seuil') : 'b';
@endphp

@unless(! $principale || request()->routeIs('hommage') || request()->routeIs('reservation.*'))
    <div class="seuil-olimpia seuil--{{ $forme }}" id="seuil-olimpia" hidden
         role="dialog" aria-modal="true" aria-labelledby="seuil-olimpia-nom">

        @if($forme === 'b')
            {{-- ═══ B — le plein cadre ═══
                 Sa photo occupe tout l'écran. Pas de carte, pas de cadre : on
                 ne pose pas un objet par-dessus le site, on le remplace le
                 temps d'un regard. --}}
            {{-- Toutes ses photos, l'une après l'autre, en fondu très lent.
                 La principale ouvre et reste la plus vue ; les autres passent
                 derrière le texte sans qu'on les attende. Rien ne clignote :
                 six secondes de pose, deux secondes et demie de fondu. --}}
            <div class="fonds" aria-hidden="true">
                @foreach($photos as $i => $photo)
                    {{-- La classe se calcule en PHP : une directive Blade dans
                         l'attribut d'un composant n'est pas compilée. --}}
                    {{-- Toutes chargées d'emblée, pas seulement la première :
                         une photo qui arrive pendant son propre fondu laisse un
                         trou noir au milieu du passage. --}}
                    <x-img :class="$i === 0 ? 'fond vue' : 'fond'" :src="$photo" alt=""
                           sizes="100vw" :urgent="true" />
                @endforeach
            </div>
            <span class="fondu" aria-hidden="true"></span>
            {{-- Le grain de la maison, posé sur elle. Il donne à la photo la
                 matière qu'elle a perdue en passant par la messagerie, et il
                 la raccorde au reste du site. --}}
            <span class="grain" aria-hidden="true"></span>
        @else
            <div class="voile" aria-hidden="true"></div>
        @endif

        <div class="feuille">

            @if($forme === 'c')
                <span class="lueur" aria-hidden="true"></span>
            @endif

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

                @if($forme === 'a')
                    {{-- ═══ A — le faire-part ═══
                         Une carte d'ivoire, encre sombre, un filet d'or : ce
                         qu'on garde dans un tiroir. Le site est nuit partout —
                         une carte claire posée dessus se lit comme un objet,
                         pas comme une fenêtre de plus. --}}
                    <span class="photo">
                        <x-img :src="$principale"
                               alt="Olimpia Maryliss Country, femelle Maine Coon blanche"
                               sizes="(max-width:700px) 92vw, 460px" :urgent="true" />
                    </span>
                @elseif($forme === 'c')
                    <span class="medaillon">
                        <x-img :src="$principale"
                               alt="Olimpia Maryliss Country, femelle Maine Coon blanche"
                               sizes="240px" :urgent="true" />
                    </span>
                @endif

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
