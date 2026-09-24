<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Cat;
use App\Models\Kitten;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Rejoue le site en HTML pur.
 *
 * Le but est de pouvoir montrer le site sans louer quoi que ce soit : une
 * page GitHub, un dossier partage, une cle USB. Chaque adresse est demandee
 * a l'application comme le ferait un navigateur, la reponse est ecrite sur
 * le disque, puis tous les liens sont repris en relatif.
 *
 * Le relatif plutot que l'absolu : l'export marche alors a la racine d'un
 * domaine, dans un sous-dossier (https://untel.github.io/templedesfees/) et
 * meme ouvert depuis le disque, sans rien reconfigurer.
 *
 *   php artisan site:export
 *   php artisan site:export --sortie=docs --base=https://untel.github.io/templedesfees
 */
class ExportStatique extends Command
{
    protected $signature = 'site:export
        {--sortie=docs : Dossier de destination, relatif a la racine du projet}
        {--base= : Adresse publique de l\'apercu, pour les balises canonique et Open Graph}
        {--garder : Ne pas vider le dossier de destination avant d\'ecrire}';

    protected $description = 'Exporte le site en HTML statique, pret a publier sans serveur';

    /** Les fichiers et dossiers de public/ qui accompagnent les pages. */
    private const ASSETS = ['build', 'fonts', 'images', 'favicon.ico'];

    public function handle(): int
    {
        $sortie = base_path($this->option('sortie'));
        $base   = rtrim((string) $this->option('base'), '/');

        /*
         * Les formulaires n'ont personne pour les recevoir : les vues le
         * disent d'elles-memes des que l'indicateur est leve.
         */
        config(['chatterie.apercu_statique' => true]);

        if (! $this->option('garder') && File::exists($sortie)) {
            File::deleteDirectory($sortie);
        }
        File::ensureDirectoryExists($sortie);

        $adresses = $this->adresses();
        $this->info(count($adresses).' pages à écrire dans '.$this->option('sortie').'/');

        $barre = $this->output->createProgressBar(count($adresses));
        $barre->start();

        $ecrites = 0;
        $manquees = [];

        foreach ($adresses as $adresse) {
            $html = $this->rendre($adresse, $code);

            if ($code !== 200) {
                $manquees[] = $adresse.' ('.$code.')';
                $barre->advance();

                continue;
            }

            $chemin = $this->cheminDe($adresse);
            File::ensureDirectoryExists(dirname("$sortie/$chemin"));
            File::put("$sortie/$chemin", $this->reprendreLiens($html, $chemin, $base));

            $ecrites++;
            $barre->advance();
        }

        $barre->finish();
        $this->newLine(2);

        $this->pageIntrouvable($sortie, $base);
        $this->assets($sortie);
        $this->fichiersDeService($sortie);

        $this->info("$ecrites pages écrites.");

        if ($manquees) {
            $this->warn('Écartées : '.implode(', ', $manquees));
        }

        $this->newLine();
        $this->line('  Publier sur GitHub Pages : pousser le dépôt, puis dans');
        $this->line('  Settings › Pages, choisir la branche et le dossier /'.$this->option('sortie').'.');

        return self::SUCCESS;
    }

    /** Toutes les adresses a figer, fiches comprises. */
    private function adresses(): array
    {
        $fixes = ['/', '/chatons', '/elevage', '/le-maine-coon', '/articles',
                  '/galerie', '/questions', '/contact', '/adopter', '/mentions-legales',
                  '/olimpia'];

        $fiches = collect()
            ->merge(Cat::all()->map(fn (Cat $c) => '/elevage/'.$c->slug))
            ->merge(Kitten::publies()->get()->map(fn (Kitten $k) => '/chatons/'.$k->slug))
            ->merge(Article::publies()->get()->map(fn (Article $a) => '/articles/'.$a->slug));

        return array_merge($fixes, $fiches->all());
    }

    /** Demande une adresse a l'application, comme le ferait un navigateur. */
    private function rendre(string $adresse, ?int &$code = null): string
    {
        /* L'adresse est construite sur APP_URL : sans cela Request::create()
           prendrait « localhost » et les liens produits par route() ne
           correspondraient plus a la base que l'on retire ensuite. */
        $noyau = app(Kernel::class);
        $reponse = $noyau->handle(Request::create(rtrim(config('app.url'), '/').$adresse, 'GET'));
        $code = $reponse->getStatusCode();

        return $reponse->getContent();
    }

    /**
     * Le chemin du fichier pour une adresse.
     *
     * Chaque page devient un index.html dans son propre dossier : l'adresse
     * affichee reste /chatons/ et non /chatons.html, exactement comme sur le
     * site servi par PHP.
     */
    private function cheminDe(string $adresse): string
    {
        $adresse = trim($adresse, '/');

        return $adresse === '' ? 'index.html' : "$adresse/index.html";
    }

    /**
     * Repasse tous les liens en relatif.
     *
     * Les adresses produites par route() et asset() sont absolues et portent
     * APP_URL. On les ramene d'abord a la racine, puis on les prefixe du
     * nombre de « ../ » qui separe la page en cours de la racine.
     */
    private function reprendreLiens(string $html, string $chemin, string $base): string
    {
        $html = $this->racine($html);

        // La page est a <profondeur> dossiers de la racine.
        $profondeur = substr_count($chemin, '/');
        $remonte = $profondeur === 0 ? './' : str_repeat('../', $profondeur);

        /*
         * Les balises qui doivent rester absolues : une adresse canonique ou
         * une image Open Graph relative n'a aucun sens pour un robot ou pour
         * un aperçu de partage. Elles sont mises de cote, puis rendues.
         */
        $abris = [];
        $html = preg_replace_callback(
            '#<(?:link[^>]*rel="canonical"|meta[^>]*(?:property="og:(?:url|image)"|name="twitter:image"))[^>]*>#i',
            function (array $m) use (&$abris, $base) {
                $cle = '@@ABRI'.count($abris).'@@';
                /*
                 * L'accueil est un cas a part : url()->current() n'y porte pas
                 * de barre finale, et la mise a la racine le reduisait a une
                 * chaine vide. Le canonique et l'og:url de la page d'accueil
                 * sortaient donc vides — ce qui casse l'apercu du lien au
                 * moment meme ou on le partage.
                 */
                $abris[$cle] = $base
                    ? str_replace(
                        ['href=""', 'content=""', 'href="/', 'content="/'],
                        ['href="'.$base.'/"', 'content="'.$base.'/"',
                         'href="'.$base.'/', 'content="'.$base.'/'],
                        $m[0],
                    )
                    : $m[0];

                return $cle;
            },
            $html
        );

        // href="/x", src="/x", action="/x" — jamais //, qui designe un autre domaine.
        /*
         * L'accueil a ete ramene a une chaine vide : href="" recharge la page
         * courante, ce qui n'est pas l'accueil. On le remet a la racine avant
         * de reprendre les liens.
         */
        $html = str_replace(['href=""', 'action=""'], ['href="/"', 'action="/"'], $html);

        $html = preg_replace_callback(
            '#\b(href|src|action|poster|data-full)="/(?!/)([^"]*)"#',
            function (array $m) use ($remonte) {
                [, $attribut, $cible] = $m;

                /*
                 * Une page exportee est un dossier portant un index.html : le
                 * lien doit donc finir par une barre. Sans elle il designe un
                 * fichier qui n'existe pas — GitHub Pages s'en tire par une
                 * redirection, mais pas un dossier ouvert depuis le disque.
                 */
                $estPage = $attribut === 'href'
                    && $cible !== ''
                    && ! str_contains($cible, '#')
                    && ! str_contains($cible, '?')
                    && ! str_contains(basename($cible), '.');

                if ($estPage) {
                    $cible = rtrim($cible, '/').'/';
                }

                return $attribut.'="'.$remonte.$cible.'"';
            },
            $html
        );

        // srcset, ou chaque candidat porte sa propre adresse.
        $html = preg_replace_callback(
            '#\bsrcset="([^"]*)"#',
            fn (array $m) => 'srcset="'.preg_replace('#(^|,\s*)/(?!/)#', '$1'.$remonte, $m[1]).'"',
            $html
        );

        return strtr($html, $abris);
    }

    /**
     * Ramene a la racine toute adresse absolue pointant vers le site
     * lui-meme : celle d'APP_URL, mais aussi les localhost et 127.0.0.1 que
     * peut produire un rendu lance depuis un autre hote.
     */
    private function racine(string $html): string
    {
        $appUrl = rtrim(config('app.url'), '/');
        $html = str_replace([$appUrl.'/', $appUrl], ['/', ''], $html);

        return preg_replace('#https?://(?:localhost|127\.0\.0\.1|\[::1\])(?::\d+)?#i', '', $html);
    }

    /**
     * La page d'erreur.
     *
     * GitHub Pages sert /404.html pour toute adresse inconnue. Elle vit a la
     * racine mais peut etre atteinte depuis n'importe quelle profondeur : ses
     * liens restent donc absolus, prefixes de la base quand elle est connue.
     */
    private function pageIntrouvable(string $sortie, string $base): void
    {
        $html = $this->rendre('/'.Str::random(24).'-introuvable', $code);

        $html = $this->racine($html);

        if ($base) {
            /*
             * Les memes attributs que pour les autres pages, srcset compris :
             * la 404 porte le meme bandeau et les memes images qu'ailleurs, et
             * une seule liste oubliee ici se voit tout de suite a l'ecran.
             */
            $html = preg_replace(
                '#\b(href|src|action|poster|data-full|content)="/(?!/)#',
                '$1="'.$base.'/',
                $html
            );

            $html = preg_replace_callback(
                '#\bsrcset="([^"]*)"#',
                fn (array $m) => 'srcset="'
                    .preg_replace('#(^|,\s*)/(?!/)#', '$1'.$base.'/', $m[1]).'"',
                $html
            );

            /*
             * Le canonique et l'og:url portaient l'adresse tiree au sort qui a
             * servi a obtenir la page : on ne declare pas comme adresse de
             * reference une page qui n'existe pas. Ils designent l'accueil.
             */
            $html = preg_replace(
                '#(<(?:link[^>]*rel="canonical"|meta[^>]*property="og:url")[^>]*(?:href|content)=")[^"]*(")#i',
                '${1}'.$base.'/$2',
                $html
            );
        }

        File::put("$sortie/404.html", $html);
        $this->line('  404.html');
    }

    /** Les feuilles, scripts, polices et images qui accompagnent les pages. */
    private function assets(string $sortie): void
    {
        foreach (self::ASSETS as $element) {
            $source = public_path($element);

            if (! File::exists($source)) {
                continue;
            }

            if (File::isDirectory($source)) {
                File::copyDirectory($source, "$sortie/$element");
            } else {
                File::copy($source, "$sortie/$element");
            }
        }

        /*
         * Vite ecrit les adresses de ses propres images en absolu
         * (/build/assets/grain-xxx.png). Dans un sous-dossier, elles
         * tomberaient dans le vide : la feuille etant elle-meme dans
         * assets/, un simple nom de fichier suffit.
         */
        foreach (File::glob("$sortie/build/assets/*.css") as $feuille) {
            File::put($feuille, str_replace('/build/assets/', '', File::get($feuille)));
        }

        $this->line('  '.implode(', ', self::ASSETS));
    }

    /**
     * Les fichiers que GitHub Pages attend.
     *
     * .nojekyll : sans lui, Pages passe le dossier a Jekyll, qui ecarte tout
     * ce qui commence par un souligne. robots.txt : l'apercu ne doit pas se
     * retrouver indexe a cote du vrai site, ni lui faire concurrence.
     */
    private function fichiersDeService(string $sortie): void
    {
        File::put("$sortie/.nojekyll", '');
        File::put("$sortie/robots.txt", "User-agent: *\nDisallow: /\n");

        $this->line('  .nojekyll, robots.txt (aperçu non indexé)');
    }
}
