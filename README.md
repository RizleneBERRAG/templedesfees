# Chatterie du Temple des Fées

Site de la Chatterie du Temple des Fées — élevage de Maine Coon à
Lapeyrouse-Mornay (26210), Drôme. Laravel 12, Blade, MySQL.

Laravel 12 et non 13 : le XAMPP de la machine de dev sert le site en PHP 8.2,
et Laravel 13 exige PHP 8.3. Laravel 12 accepte PHP 8.2 et c'est aussi la
version la mieux couverte par Filament.

Refonte réalisée par Net Strategy.

## Installation

```powershell
composer install
npm install
npm run build          # ou `npm run dev` pendant le développement
php artisan key:generate
```

> PowerShell 5 ne comprend pas `&&` : enchaîner les commandes avec `;`
> ou les lancer une par une.

Créer la base dans phpMyAdmin (XAMPP) :

```sql
CREATE DATABASE templedesfees CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Puis :

```bash
php artisan migrate --seed
```

Le site est accessible sur **http://127.0.0.1:8000** (`php artisan serve`)
ou sur `http://localhost/templedesfees/public` via Apache.

Compte de départ du back-office : `letempledesfees@outlook.fr` / `templedesfees`
— à changer à la première connexion.

## Attention : deux PHP sur cette machine

`php` en ligne de commande est un **Herd Lite 8.4 sans l'extension `intl`**,
alors qu'Apache sert le site en **8.2 avec `intl`**. Les tests du back-office
échouent avec le premier et passent avec le second. Lancer la suite avec :

```bash
C:\xampp\php\php.exe artisan test
```

## La charte graphique

`resources/css/app.css` — direction « Sanctuaire » : nuit d'émeraude, dorure,
ivoire. Trois polices auto-hébergées dans `public/fonts`, aucun appel à Google.

- **Cinzel** — capitales romaines : la marque, les titres de chapitre, les noms.
- **Cormorant Garamond** — les grands titres et les citations en italique.
- **Jost** — le texte courant et les étiquettes.

Deux règles à ne pas contourner :

1. **L'or n'est jamais un aplat.** C'est toujours le dégradé `--dorure`, appliqué
   au remplissage d'un texte (classe `.or`) ou à un filet. Un jaune plat casse
   l'illusion de dorure immédiatement.
2. **L'arche est la signature.** Composant `.arche`, trois filets successifs.
   Elle encadre tous les portraits de chats, du hero à la vignette de 60 px.

Le site se lit comme un récit : chaque section de l'accueil est un chapitre
numéroté, annoncé par une frise (`<x-fleuron>`).

## Ce qui est en place

| Domaine | Fichiers |
|---|---|
| Reproducteurs et dépistages | `app/Models/Cat.php`, `HealthTest.php`, `app/Enums/HealthTestType.php` |
| Portées, chatons, suivi | `Litter.php`, `Kitten.php`, `LitterEvent.php` |
| Dossiers adoptants (RGPD) | `AdoptionRequest.php`, `app/Console/Commands/PurgeRgpd.php` |
| Messages de contact (RGPD) | `ContactMessage.php`, `ContactController.php` |
| Réglages et mentions légales | `Setting.php` |
| Pages publiques | `routes/web.php`, `app/Http/Controllers/`, `resources/views/pages/` |
| Charte graphique | `resources/css/app.css` |
| Contenu éditorial fixe | `config/chatterie.php` |
| Contenu de démarrage | `database/seeders/data/content.php` |

## Les dépistages du Maine Coon

La cardiomyopathie hypertrophique est la maladie de la race, et elle se dépiste
de **deux façons qui ne se remplacent pas** :

- le **test génétique MyBPC3** cherche les mutations connues — une fois pour la vie ;
- l'**échocardiographie** regarde le cœur tel qu'il est le jour de l'examen, et
  se renouvelle tant que le chat reproduit.

Un chat indemne génétiquement peut développer une HCM d'une autre origine. Un
élevage qui n'affiche que le test ADN n'a fait que la moitié du chemin.
S'y ajoutent la SMA et le PK-Def (test ADN), la dysplasie de la hanche
(radiographie cotée) et le dépistage FIV/FeLV.

Voir `app/Enums/HealthTestType.php`.

## La règle métier à ne pas contourner

Une fiche chaton **ne peut pas être publiée** tant que son numéro
d'identification ICAD et le numéro de portée LOOF sont vides. C'est une
obligation légale (annonces de cession d'animaux de compagnie), appliquée à
trois niveaux :

1. `Kitten::estPubliable()` — la règle elle-même ;
2. `App\Observers\KittenObserver` — repasse `est_publie` à `false` à chaque
   enregistrement si les numéros manquent, même si la case est cochée ;
3. `Kitten::scopePublies()` — utilisé par toutes les requêtes du site public,
   et `KittenController::show()` renvoie un 404 sur une fiche non publiée.

Le seeder laisse volontairement ces numéros vides : au premier lancement, les
fiches chatons sont donc en brouillon. C'est le comportement attendu.

```powershell
php artisan demo:numeros           # remplit les numéros, publie les fiches
php artisan demo:numeros --reset   # vide les numéros, tout repasse en brouillon
```

Faire l'aller-retour devant la cliente est la démonstration la plus parlante de
la règle. La commande est à supprimer une fois le back-office en place.

Aucun nom d'adoptant n'est jamais affiché côté public. Les statuts
« réservé » et « adopté » portent sur le chaton, pas sur la famille.

## Les données de démarrage

**Les onze chats sont réels** — noms, sexes, robes et années de naissance repris
des fiches publiques du site actuel de la chatterie.

**Les numéros d'identification ne le sont pas.** Le site actuel publie le numéro
de puce de chaque reproducteur en clair ; rien ne l'impose, l'obligation
d'affichage portant sur les annonces de cession, donc sur les chatons à vendre.
Ils sont laissés vides et se saisissent depuis le back-office.

**La portée et les chatons sont une démonstration.** Le site actuel n'annonce
aucune portée en cours. À remplacer par la vraie avant toute mise en ligne.

**Les textes de présentation des chats sont à écrire avec l'éleveuse** : ils
portent tous un `[TEXTE À ÉCRIRE]` bien visible.

## Reste à faire

Par ordre de priorité :

1. **Les photos de chatons et de la maison.** Les onze chats ont leur vraie
   photo, reprise de leur fiche sur le site en ligne. Il manque les chatons
   (5 emplacements : `chaton-1` à `chaton-4` et `portee-b`), occupés par des
   repères qui portent le nom du fichier attendu.

   **Ce que je n'ai volontairement pas repris du site en ligne**, et pourquoi :
   - le **bandeau d'accueil** est un montage qui porte l'ancien logo doré, les
     coordonnées de la chatterie et un nom incrustés dans l'image ;
   - les **illustrations des pages d'information** (alimentation, santé, eau,
     origines) sont des photos de banque d'images, et ce ne sont pas des
     Maine Coon ;
   - la photo « à propos » porte le filigrane **« © Julia Bénard |
     arkuswork.com »** : c'est le tirage de contrôle d'une photographe
     professionnelle, à ne pas republier sans sa licence. À signaler à
     l'éleveuse, qui l'utilise peut-être sans le savoir.
2. **Les textes de présentation des chats**, à écrire avec l'éleveuse. Ils
   portent tous un `[TEXTE À ÉCRIRE]` bien visible dans
   `database/seeders/data/content.php`.
3. **La vraie portée** à la place de la portée B de démonstration.
4. **Les mentions légales obligatoires** : SIREN, certificat de capacité,
   directeur de publication, hébergeur. Elles s'affichent « À compléter » en or
   tant qu'elles manquent, ce qui est volontaire.
5. **Envoi des emails** de notification et d'accusé de réception
   (`AdoptionController::store`), une fois le SMTP configuré.
6. **Repositionner les repères de morphologie** (`config/chatterie.php`, clé
   `morphologie`) quand la photo de la page « Le Maine Coon » sera remplacée :
   les coordonnées sont en pourcentages sur l'image actuelle.

Idée non faite, à discuter : éclater « Le Maine Coon » en un hub plus quatre
pages filles (origines, besoins, santé, préparation). La page actuelle couvre
les quatre sujets avec des ancres ; quatre URL distinctes ranqueraient sur
quatre requêtes différentes.

## Carte de la page Contact

Dessinée dans la page, en SVG — aucune tuile, aucune clé, aucun traceur, rien
qui vienne de l'extérieur. Le composant est
`resources/views/components/carte-situation.blade.php`.

Les villes ne sont pas placées à la main : elles sont projetées depuis leurs
coordonnées réelles, dans `config/chatterie.php`, clé `carte`. Corriger une
latitude là-bas déplace le point sur la carte. Les repères visent la commune et
non le portail : la chatterie publie son adresse complète sur son site actuel,
le nouveau ne le fait pas par défaut.

Le fond de plan précédent venait des tuiles sombres de CARTO, qui réclament
désormais une clé : la carte s'affichait barrée de « API KEY REQUIRED ».
Leaflet a été retiré du projet à cette occasion (160 ko de moins).

## Aperçu statique (GitHub Pages)

Le site se rejoue en HTML pur, pour être montré sans louer d'hébergement :

```bash
php artisan site:export
```

Chaque adresse est demandée à l'application comme le ferait un navigateur, la
réponse est écrite dans `docs/`, puis **tous les liens sont repris en
relatif**. L'export fonctionne donc à la racine d'un domaine, dans un
sous-dossier (`https://untel.github.io/templedesfees/`) et même ouvert depuis
le disque, sans rien reconfigurer.

Pour publier : pousser le dépôt, puis dans **Settings › Pages**, choisir la
branche et le dossier **/docs**. Aucune action GitHub à écrire, aucun PHP côté
serveur.

Options :

| Option | Effet |
| --- | --- |
| `--sortie=docs` | Dossier de destination (défaut : `docs`) |
| `--base=https://untel.github.io/templedesfees` | Rend absolues les balises canonique et Open Graph, pour un partage propre |
| `--garder` | N'efface pas le dossier avant d'écrire |

Ce que l'export prévoit :

- `.nojekyll`, sans quoi GitHub passerait le dossier à Jekyll ;
- un `robots.txt` interdisant l'indexation et une balise `noindex` sur chaque
  page — l'aperçu ne doit pas se retrouver référencé à côté du vrai site, les
  deux y perdraient ;
- `404.html`, que GitHub Pages sert pour toute adresse inconnue ;
- les trois formulaires (contact, avis, pré-réservation) restent affichés et
  manipulables, mais coiffés d'une note qui dit qu'il n'y a pas de serveur
  derrière, et qui donne le téléphone et l'adresse électronique. C'est
  l'indicateur `chatterie.apercu_statique` qui les bascule, posé par la
  commande.

Ce que l'export ne contient pas : le back-office Filament, qui a besoin de PHP
et de la base. L'aperçu ne montre que le site public.

**Refaire l'export après chaque changement**, sinon `docs/` reste sur
l'ancienne version :

```bash
npm run build
php artisan site:export
```
