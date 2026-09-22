<?php

/*
 * Contenu editorial fixe du site de la Chatterie du Temple des Fees.
 *
 * - 'morphologie'    : les reperes cliquables de la page « Le Maine Coon ».
 *                      x et y sont des pourcentages sur la photo affichee ;
 *                      changer de photo impose de les repositionner.
 * - 'couverture'     : le detail de ce que couvre l'adoption (page Adopter).
 * - 'carte'          : la carte de la page Contact.
 * - 'back_office'    : qui a le droit d'entrer dans l'espace de gestion.
 * - 'anciennes_urls' : les adresses du site actuel, a rediriger le jour de la
 *                      bascule.
 *
 * Ces blocs ne bougent quasiment jamais : ils restent en configuration plutot
 * qu'en base. Le reste du contenu (chats, portees, chatons, questions) vit en
 * base de donnees et se modifie depuis le back-office.
 */

return [

    /*
     * Lire un Maine Coon.
     *
     * Rien n'est decoratif chez cette race : chaque trait vient des hivers du
     * Nord-Est americain, ou seuls tenaient les chats bien equipes. C'est
     * l'angle de la page — expliquer a quoi sert ce qu'on regarde.
     *
     * Les coordonnees sont calees sur images/cats/hero-duo.webp. A reprendre
     * quand l'eleveuse fournira ses propres photos.
     */
    'morphologie' => [
        [
            'x' => 52,
            'y' => 16,
            'categorie' => 'Oreilles',
            'titre' => 'Lynx tips',
            'texte' => 'Les touffes de poils qui prolongent la pointe de l\'oreille. Elles brisent le vent et protègent le conduit du froid. Grandes oreilles larges à la base, plantées haut : c\'est l\'un des traits les plus recherchés du standard.',
        ],
        [
            'x' => 54,
            'y' => 27,
            'categorie' => 'Tête',
            'titre' => 'Museau carré',
            'texte' => 'Le museau du Maine Coon se coupe net, en angle droit vu de profil, avec un menton ferme aligné sous le nez. C\'est ce qui lui donne son air sérieux, et c\'est un point que les juges regardent en premier.',
        ],
        [
            'x' => 49,
            'y' => 43,
            'categorie' => 'Poil',
            'titre' => 'Collerette',
            'texte' => 'La fraise de poils longs autour du cou, très marquée en hiver, beaucoup plus discrète après la mue de printemps. Elle protège la gorge — la zone la plus exposée quand le chat dort en boule dans la neige.',
        ],
        [
            'x' => 40,
            'y' => 62,
            'categorie' => 'Fourrure',
            'titre' => 'Poil mi-long hydrofuge',
            'texte' => 'Court sur les épaules, long sur les flancs et la culotte. Cette répartition n\'est pas un hasard : elle laisse l\'épaule libre pour marcher et couvre ce qui touche le sol. Le poil repousse l\'eau plutôt que de l\'absorber.',
        ],
        [
            'x' => 28,
            'y' => 80,
            'categorie' => 'Queue',
            'titre' => 'Queue en panache',
            'texte' => 'Longue — elle doit atteindre au moins l\'épaule quand on la rabat — et très fournie. Le chat s\'en couvre le museau et les coussinets pour dormir. C\'est une couverture, pas un ornement.',
        ],
        [
            'x' => 66,
            'y' => 55,
            'categorie' => 'Ossature',
            'titre' => 'Corps rectangulaire',
            'texte' => 'Poitrine large, ossature lourde, corps plus long que haut. Un mâle adulte pèse entre six et neuf kilos, et met trois à quatre ans à finir de se construire — deux fois plus longtemps qu\'un chat de gouttière.',
        ],
    ],

    /*
     * Ce que couvre l'adoption.
     *
     * Le prix d'un chaton n'est pas le prix de l'animal : c'est le prix des
     * neuf mois qui le precedent. Cette liste les detaille, poste par poste.
     */
    'couverture' => [
        [
            'numero' => '01',
            'titre' => 'Saillie et suivi de gestation',
            'detail' => 'Échographie de confirmation, alimentation spécifique de la mère pendant neuf semaines, visites vétérinaires.',
            'quand' => 'Avant la naissance',
        ],
        [
            'numero' => '02',
            'titre' => 'Mise bas et première semaine',
            'detail' => 'Surveillance continue jour et nuit, pesée quotidienne, aide à la tétée si un chaton décroche.',
            'quand' => 'Semaine 1',
        ],
        [
            'numero' => '03',
            'titre' => 'Douze semaines de nourrissage',
            'detail' => 'Lait maternisé au besoin, puis pâtée et croquettes kitten de qualité, pour la mère comme pour les petits. Un Maine Coon mange beaucoup, et sa mère encore plus pendant l\'allaitement.',
            'quand' => 'Semaines 1 à 12',
        ],
        [
            'numero' => '04',
            'titre' => 'Deux vermifugations',
            'detail' => 'Protocole complet, renouvelé avant le départ.',
            'quand' => 'Semaines 5 et 9',
        ],
        [
            'numero' => '05',
            'titre' => 'Identification ICAD',
            'detail' => 'Puce électronique posée et enregistrée au nom de l\'élevage, puis transférée à la famille.',
            'quand' => 'Semaine 6',
        ],
        [
            'numero' => '06',
            'titre' => 'Primo-vaccination et rappel',
            'detail' => 'Typhus et coryza, deux injections, carnet de santé tenu à jour.',
            'quand' => 'Semaines 8 et 12',
        ],
        [
            'numero' => '07',
            'titre' => 'Certificat vétérinaire de bonne santé',
            'detail' => 'Établi moins de huit jours avant la cession, obligatoire et remis en main propre.',
            'quand' => 'Avant le départ',
        ],
        [
            'numero' => '08',
            'titre' => 'Inscription LOOF et pedigree',
            'detail' => 'Déclaration de saillie, déclaration de portée, édition du pedigree officiel.',
            'quand' => 'Semaines 1 à 12',
        ],
        [
            'numero' => '09',
            'titre' => 'Dépistages des parents',
            'detail' => 'HCM par test ADN et par échocardiographie, SMA et PK-Def par test ADN, radiographie de hanche, dépistage FIV/FeLV.',
            'quand' => 'Toute l\'année',
        ],
        [
            'numero' => '10',
            'titre' => 'Socialisation quotidienne',
            'detail' => 'Manipulation dès la naissance, habituation aux bruits, aux enfants, au chien, au transport et à la voiture.',
            'quand' => 'Chaque jour',
        ],
        [
            'numero' => '11',
            'titre' => 'Contrat et document d\'information',
            'detail' => 'Contrat de cession écrit, document d\'information sur les besoins de l\'espèce, conseils d\'arrivée.',
            'quand' => 'Au départ',
        ],
        [
            'numero' => '12',
            'titre' => 'Suivi après le départ',
            'detail' => 'Disponibilité à vie pour les questions, et reprise du chat si votre situation change.',
            'quand' => 'Sans limite',
        ],
    ],

    /*
     * Carte de la page Contact.
     *
     * Coordonnees de la commune, pas du portail : elles situent l'elevage a la
     * bonne place sur une carte et dans les donnees structurees sans publier un
     * point GPS exact. La chatterie affiche son adresse complete sur son site
     * actuel — si elle souhaite que la carte y pointe precisement, il suffit de
     * remplacer ces coordonnees et d'ajuster le rayon.
     */
    'carte' => [
        'zone' => [
            'lat'    => 45.2833,
            'lng'    => 4.9833,
            'rayon'  => 1600,          // metres
            'titre'  => 'Lapeyrouse-Mornay',
            'detail' => "L'élevage — chemin Saint-Charles",
        ],
        'reperes' => [
            ['lat' => 45.2408, 'lng' => 4.8147, 'titre' => "Saint-Rambert-d'Albon", 'detail' => '10 min en voiture'],
            ['lat' => 45.0453, 'lng' => 4.8705, 'titre' => 'Valence',               'detail' => '45 min par la D538'],
            ['lat' => 45.4397, 'lng' => 4.3872, 'titre' => 'Saint-Étienne',         'detail' => "1 h par la N88"],
            ['lat' => 45.7640, 'lng' => 4.8357, 'titre' => 'Lyon',                  'detail' => "1 h par l'A7"],
        ],
    ],

    /*
     * Qui entre dans l'espace de gestion. La liste est ici, en clair, et non
     * dans un role en base : ouvrir le back-office a quelqu'un doit etre une
     * decision, pas un effet de bord de la creation d'un compte.
     */
    'back_office' => [
        'emails' => [
            'letempledesfees@outlook.fr',
        ],
    ],

    /*
     * Adresses du site actuel.
     *
     * Le site en ligne est un Next.js dont toutes les URL sont en anglais
     * (/cats, /gallery, /infos/health) alors que le contenu est francais. La
     * refonte les repasse en francais ; ces redirections permanentes evitent
     * que les liens deja partages, les signets et les resultats de recherche
     * existants ne tombent dans le vide le jour de la bascule.
     *
     * /sign-in n'y figure volontairement pas : la page de connexion du site
     * actuel est indexable et liee depuis le menu et le pied de page, ce qui
     * n'a aucune raison d'etre. Elle disparait, sans redirection.
     */
    'anciennes_urls' => [
        'cats'              => 'cats.index',
        'gallery'           => 'gallery',
        'infos'             => 'breed',
        'infos/origins'     => 'breed',
        'infos/nutrition'   => 'breed',
        'infos/health'      => 'breed',
        'infos/preparation' => 'breed',
        'articles'          => 'home',
        'about'             => 'home',
        'legal'             => 'legal',
        'legal/mentions'    => 'legal',
        'legal/privacy'     => 'legal',
        'legal/terms'       => 'legal',
    ],
];
