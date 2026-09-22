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

- Les pages intérieures dans la nouvelle charte (fiches chat et chaton,
  Le Maine Coon, galerie, contact, adopter, questions, mentions)
- Les vraies photos — les emplacements sont occupés par des repères générés
  qui portent le nom du fichier attendu
- Envoi des emails de notification et d'accusé de réception (`AdoptionController::store`)

## Carte de la page Contact

Leaflet + fond sombre CartoDB, sans clé API et sans traceur. Les coordonnées et
les temps de trajet sont dans `config/chatterie.php`, clé `carte`.
Elles visent la commune, pas le portail : la chatterie publie son adresse
complète sur son site actuel, mais le site ne le fait pas par défaut.
