@extends('layouts.app')

@section('title', "Le Maine Coon — origines, morphologie, caractère et santé")
@section('description', "Tout sur le Maine Coon avant d'en accueillir un : origines, lecture de la morphologie, robe, caractère réel, besoins quotidiens, dépistages de la race et préparation de l'arrivée.")

@push('schema')
    <x-fil-ariane :etapes="[
        ['nom' => 'Accueil',       'url' => route('home')],
        ['nom' => 'Le Maine Coon', 'url' => route('breed')],
    ]" />
@endpush

@section('content')

<section class="bande">
    <div class="wrap">
        <div class="frise" style="margin-bottom:clamp(30px,4vw,50px)"><x-fleuron taille="grand" /></div>

        <x-section-head
            class="monte"
            niveau="1"
            eyebrow="La race"
            titre="Le Maine Coon"
            lede="Le plus grand chat domestique, et l'un des plus doux. Ce qu'il faut savoir avant d'en accueillir un — y compris ce qui pourrait vous faire changer d'avis." />

        {{-- Un sommaire plutôt qu'un fil d'Ariane : la page est longue, et
             chacun n'y cherche pas la même chose. --}}
        <nav class="sommaire" aria-label="Sommaire de la page">
            <a href="#origines">Origines</a>
            <a href="#morphologie">Le lire</a>
            <a href="#robe">La robe</a>
            <a href="#caractere">Le caractère</a>
            <a href="#besoins">Ses besoins</a>
            <a href="#sante">Sa santé</a>
            <a href="#arrivee">Son arrivée</a>
        </nav>
    </div>
</section>

{{-- ═══ origines ═══ --}}
<section class="bande creuse" id="origines">
    <div class="wrap">
        <div class="duo-texte inverse monte">
            <figure class="vue haute">
                <img src="{{ asset('images/cats/solanna.webp') }}"
                     alt="Maine Coon adulte, collerette d'hiver" loading="lazy">
                <figcaption>Collerette d'hiver</figcaption>
            </figure>
            <div class="pile">
                <span class="numero" style="font-family:var(--pierre);font-size:12px;letter-spacing:.34em;text-transform:uppercase;color:var(--or-mat)">Chapitre premier</span>
                <h2>Une race née dans<br>les hivers du Maine</h2>
                <p class="lede lettrine">
                    Le Maine Coon est l'une des plus anciennes races naturelles d'Amérique du Nord.
                    Personne ne l'a dessiné : il s'est formé tout seul dans le Nord-Est des
                    États-Unis, où seuls tenaient les chats à fourrure dense, à grandes pattes et
                    à ossature lourde.
                </p>
                <p class="lede">
                    C'est la clé pour le comprendre : <em>rien chez lui n'est décoratif</em>.
                    La collerette protège la gorge, les touffes entre les coussinets font raquette
                    sur la neige, la queue en panache sert de couverture. Ce qu'on admire
                    aujourd'hui en exposition était d'abord de l'équipement de survie.
                </p>
                <p class="lede">
                    Son succès en expositions félines a ensuite fixé le standard moderne, et sa
                    réputation de géant doux a fait le reste.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ lecture ═══ --}}
<section class="bande" id="morphologie">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre second"
            titre="Lire un Maine Coon"
            lede="Six points qu'un juge regarde, et à quoi chacun sert vraiment. Survolez les repères." />

        @php($points = config('chatterie.morphologie'))

        <div class="lecteur monte">
            <div class="lecture" id="lecture"
                 data-points="{{ json_encode(collect($points)->map(fn ($p) => ['k' => $p['categorie'], 't' => $p['titre'], 'd' => $p['texte']]), JSON_UNESCAPED_UNICODE) }}">
                <img src="{{ asset('images/cats/tika.webp') }}"
                     alt="Tika, Maine Coon red de la chatterie, vue de profil"
                     width="1200" height="1714" loading="lazy">
                @foreach($points as $i => $p)
                    <button class="repere" type="button"
                            style="left:{{ $p['x'] }}%;top:{{ $p['y'] }}%"
                            data-point="{{ $i }}"
                            aria-pressed="{{ $i === 0 ? 'true' : 'false' }}"
                            aria-label="{{ $p['titre'] }}"><span class="onde" aria-hidden="true"></span></button>
                @endforeach
            </div>
            <div class="lecture-info" id="lecture-info">
                {{-- Rempli par app.js au survol. Le premier repère est déjà écrit
                     côté serveur : sans JavaScript, la page reste complète. --}}
                <span class="k">{{ $points[0]['categorie'] }}</span>
                <h3>{{ $points[0]['titre'] }}</h3>
                <p>{{ $points[0]['texte'] }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ robe ═══ --}}
<section class="bande creuse" id="robe">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre troisième"
            titre="La robe"
            lede="Le standard admet une palette très large. Voici le vocabulaire qu'on emploie sur les fiches de nos chats." />

        <div class="cellules monte">
            <div class="cellule">
                <span class="n">Motif</span>
                <h3>Tabby</h3>
                <p>Classic (marbré), mackerel (rayé) ou spotted (tacheté). Le motif le plus répandu de la race, avec le M du front comme signature.</p>
            </div>
            <div class="cellule">
                <span class="n">Effet</span>
                <h3>Silver et smoke</h3>
                <p>Le silver éclaircit la base du poil et fait ressortir le motif ; le smoke laisse un sous-poil clair sous une pointe foncée. La robe change complètement selon la lumière et le mouvement.</p>
            </div>
            <div class="cellule">
                <span class="n">Couleur</span>
                <h3>Tortie et écaille</h3>
                <p>Deux couleurs mêlées sur le même chat, presque toujours une femelle. Le black tortie et le bleu tortie sont deux de nos robes les plus présentes.</p>
            </div>
            <div class="cellule">
                <span class="n">Couleur</span>
                <h3>Solid, red, blanc</h3>
                <p>Uni, du noir au crème. Chez un chat entièrement blanc, on demande en plus un test d'audition : la surdité congénitale y est plus fréquente.</p>
            </div>
        </div>

        {{-- Quatre robes de l'élevage plutôt qu'une définition abstraite : le
             vocabulaire du standard prend son sens quand on voit à quoi il
             s'applique. --}}
        <div class="retable monte" style="margin-top:clamp(30px,4vw,46px)">
            @foreach([
                ['delenn',  'Black smoke',                      'Sous-poil clair, pointe noire'],
                ['boonie',  'Black tortie silver ticked tabby',  'Écaille sur fond silver'],
                ['helios',  'Red',                               'Roux franc, sans silver'],
                ['alaska',  'Blanche',                           'Blanc uni, test d’audition demandé'],
            ] as [$fichier, $robe, $detail])
                <figure class="portrait" style="width:min(250px,84vw);margin:0">
                    <div class="arche petite">
                        <i><u>
                            <img src="{{ asset('images/cats/'.$fichier.'.webp') }}"
                                 alt="Maine Coon de la chatterie, robe {{ \Illuminate\Support\Str::lower($robe) }}"
                                 width="1200" height="1714" loading="lazy">
                        </u></i>
                    </div>
                    <figcaption style="display:flex;flex-direction:column;gap:6px;align-items:center;text-align:center">
                        <b style="font-family:var(--pierre);font-size:14px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--or-clair)">{{ $robe }}</b>
                        <small style="font-family:var(--titre);font-style:italic;font-size:16px;color:var(--ivoire-3)">{{ $detail }}</small>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <p class="lede monte" style="margin-top:clamp(28px,3.4vw,40px);margin-inline:auto;text-align:center">
            La texture compte autant que la couleur : un poil mi-long hydrofuge, court sur les
            épaules et long sur les flancs, qui donne son volume à la silhouette.
        </p>
    </div>
</section>

{{-- ═══ caractère ═══ --}}
<section class="bande" id="caractere">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre quatrième"
            titre="Ce n'est pas un chat d'appartement témoin"
            lede="Autant le dire tout de suite : le Maine Coon est doux, mais il est partout, et il est grand." />

        <div class="cellules monte">
            <div class="cellule">
                <h3>Il suit</h3>
                <p>Il vous accompagne de pièce en pièce sans rien demander. Ce n'est pas un chat de compagnie distant : c'est un chat qui s'installe là où vous êtes.</p>
            </div>
            <div class="cellule">
                <h3>Il trille</h3>
                <p>Il miaule peu, mais il trille et roucoule beaucoup, avec une petite voix qui surprend chez un chat de sept kilos.</p>
            </div>
            <div class="cellule">
                <h3>Il grandit longtemps</h3>
                <p>Trois à quatre ans pour finir de se construire, contre un an à un chat ordinaire. Le chaton que vous accueillez à douze semaines n'est qu'au début.</p>
            </div>
            <div class="cellule">
                <h3>Il occupe la place</h3>
                <p>Un mâle adulte pèse six à neuf kilos et mesure près d'un mètre queue comprise. L'arbre à chat doit être dimensionné pour lui, pas pour un chat de gouttière.</p>
            </div>
        </div>
    </div>
</section>

<figure class="bande-photo" style="--h:42vh">
    <img src="{{ asset('images/cats/kora.webp') }}"
         alt="Kora, jeune Maine Coon noire de la chatterie" loading="lazy">
    <span class="voile" aria-hidden="true"></span>
    <figcaption>Tous nos chats vivent dans la maison — pas en box, pas en cage</figcaption>
</figure>

{{-- ═══ besoins ═══ --}}
<section class="bande" id="besoins">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre cinquième"
            titre="Ses besoins au quotidien"
            lede="Un grand gabarit à poil mi-long demande trois choses de plus qu'un autre chat." />

        <div class="duo-texte monte">
            <div class="pile">
                <h3>Le poil</h3>
                <p class="lede">
                    Brossage deux à trois fois par semaine, davantage en période de mue. Ce n'est
                    pas de l'esthétique : c'est ce qui évite les nœuds sous les aisselles et la
                    culotte, et ce qui limite les boules de poils. Un Maine Coon négligé trois mois
                    part chez le toiletteur pour une tonte.
                </p>

                <h3 style="margin-top:14px">L'alimentation</h3>
                <p class="lede">
                    Des protéines animales identifiées et un apport énergétique ajusté à l'activité.
                    Croquettes et pâtée mélangées restent le meilleur compromis : la pâtée fait
                    boire, et un grand chat stérilisé a tout à y gagner côté reins. Deux à quatre
                    repas par jour plutôt qu'une gamelle pleine en permanence.
                </p>

                <h3 style="margin-top:14px">Les articulations</h3>
                <p class="lede">
                    Un couchage ferme et large, des surfaces non glissantes, et des accès en
                    hauteur progressifs — une marche intermédiaire plutôt qu'un saut d'un mètre.
                    Le poids compte double chez une race lourde.
                </p>
            </div>

            <div class="faits">
                <div class="fait"><b>6–9</b><span>kilos, mâle adulte</span></div>
                <div class="fait"><b>3–4</b><span>ans de croissance</span></div>
                <div class="fait"><b>2–3</b><span>brossages par semaine</span></div>
                <div class="fait"><b>12–16</b><span>ans d'espérance de vie</span></div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ santé ═══ --}}
<section class="bande creuse encadree" id="sante">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre sixième"
            titre="Les dépistages de la race"
            lede="Quatre maladies héréditaires se dépistent chez le Maine Coon. Nous publions les résultats de chaque reproducteur, datés, sur sa fiche." />

        <div class="cellules monte">
            <div class="cellule">
                <span class="n">La principale</span>
                <h3>HCM — cardiomyopathie hypertrophique</h3>
                <p>La maladie de la race. Elle se dépiste de <strong style="font-weight:400;color:var(--or-clair)">deux façons qui ne se remplacent pas</strong> : le test génétique MyBPC3 cherche les mutations connues, l'échocardiographie regarde le cœur tel qu'il est aujourd'hui. Un chat indemne génétiquement peut développer une HCM d'une autre origine.</p>
            </div>
            <div class="cellule">
                <span class="n">Test ADN</span>
                <h3>SMA — amyotrophie spinale</h3>
                <p>Affection héréditaire neuromusculaire. Un test génétique unique suffit pour la vie, et permet d'écarter tout risque en choisissant les accouplements.</p>
            </div>
            <div class="cellule">
                <span class="n">Test ADN</span>
                <h3>PK-Def — déficit en pyruvate kinase</h3>
                <p>Provoque une anémie. Là encore, un test unique identifie porteurs et atteints, et deux porteurs ne sont jamais accouplés ensemble.</p>
            </div>
            <div class="cellule">
                <span class="n">Radiographie</span>
                <h3>Dysplasie de la hanche</h3>
                <p>Plus fréquente chez les grandes races. Elle se dépiste par radiographie cotée, sur un animal qui a fini de grandir — donc pas avant deux ans.</p>
            </div>
        </div>

        <div class="duo-texte monte" style="margin-top:clamp(30px,4vw,46px);align-items:center">
            <figure class="vue haute" style="max-width:380px;margin-inline:auto">
                <img src="{{ asset('images/cats/uriana.webp') }}"
                     alt="Uriana, reproductrice de la chatterie, dont les dépistages sont publiés"
                     width="1200" height="1714" loading="lazy">
                <figcaption>Uriana — dépistages publiés sur sa fiche</figcaption>
            </figure>
            <div class="pile">
                <h3>Nous publions tout, y compris ce qui manque</h3>
                <p class="lede">
                    Chaque fiche de reproducteur porte les six lignes, avec leur date et leur
                    laboratoire. Une ligne encore vide s’affiche telle quelle, en or — nous ne
                    masquons pas ce qui n’est pas fait.
                </p>
                <p class="lede">
                    C’est vérifiable en une minute, et c’est exactement ce qu’un acheteur
                    sérieux vient contrôler avant de vous appeler.
                </p>
                <div class="btnrow" style="margin-top:6px">
                    <a class="btn" href="{{ route('cats.index') }}">Voir les résultats de nos chats</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ arrivée ═══ --}}
<section class="bande" id="arrivee">
    <div class="wrap">
        <x-section-head
            class="monte"
            numero="Chapitre septième"
            titre="Préparer son arrivée"
            lede="Ce qu'il faut avoir prêt le jour où le chaton entre chez vous, et ce qui se passe les premières semaines." />

        <div class="duo-texte decale monte">
            <ol class="chrono">
                <li class="faite">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Avant</span>
                    <span class="quoi">Deux gamelles larges et stables, un bac à litière spacieux, un arbre à chat solide, un transporteur rigide, une brosse et un peigne adaptés au poil mi-long.</span></span>
                </li>
                <li class="faite">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">La pièce d'accueil</span>
                    <span class="quoi">Commencez par une seule pièce calme, avec tout à portée et la litière éloignée des gamelles. On élargit ensuite au reste du logement, progressivement.</span></span>
                </li>
                <li class="faite">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Sécuriser</span>
                    <span class="quoi">Câbles rangés, fenêtres et balcons sécurisés, plantes toxiques retirées, produits ménagers hors de portée. Vérifier la stabilité de ce sur quoi il va grimper — il pèsera bientôt sept kilos.</span></span>
                </li>
                <li class="encours">
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Jour J</span>
                    <span class="quoi">Gardez d'abord la même alimentation qu'ici, puis faites la transition sur sept à dix jours. Laissez-le explorer à son rythme et limitez les visites la première semaine.</span></span>
                </li>
                <li>
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Les premières semaines</span>
                    <span class="quoi">Surveillez l'appétit, la boisson et la litière — ce sont les trois signaux qui parlent en premier. Des rituels réguliers (repas, brossage, jeu) l'installent plus vite qu'une attention permanente.</span></span>
                </li>
                <li>
                    <span class="pt" aria-hidden="true"></span>
                    <span><span class="quand">Avec les autres animaux</span>
                    <span class="quoi">Échange d'odeurs par un textile, porte entrebâillée, puis rencontres courtes et surveillées. On ne force jamais un contact : le nouveau venu doit toujours pouvoir se retirer.</span></span>
                </li>
            </ol>

            <figure class="vue haute">
                <img src="{{ asset('images/cats/aneora.webp') }}"
                     alt="Chaton Maine Coon de la chatterie, première semaine" loading="lazy">
                <figcaption>Les chatons naissent et grandissent dans le salon</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="bande creuse">
    <div class="wrap" style="display:flex;flex-direction:column;align-items:center;gap:24px;text-align:center">
        <x-fleuron taille="petit" style="color:var(--or-mat)" />
        <h2 style="font-size:clamp(1.9rem,3.6vw,2.8rem)">Une question qui n'est pas ici ?</h2>
        <div class="btnrow" style="justify-content:center">
            <a class="btn" href="{{ route('kittens.index') }}">Les chatons disponibles</a>
            <a class="btn creux" href="{{ route('faq') }}">Questions fréquentes</a>
            <a class="btn creux" href="{{ route('contact') }}">Nous écrire</a>
        </div>
    </div>
</section>

@endsection
