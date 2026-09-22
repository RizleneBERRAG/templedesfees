<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Kitten;
use Illuminate\Http\Response;

/**
 * Sitemap et robots.txt, servis par l'application et non deposes en fichiers.
 *
 * La directive Sitemap d'un robots.txt exige une adresse absolue : un fichier
 * statique la figerait sur le domaine du jour ou il a ete ecrit, et il faudrait
 * penser a le corriger a la mise en ligne. Ici l'adresse suit le site.
 */
class SitemapController extends Controller
{
    /** Pages fixes, avec leur importance relative et leur rythme de changement. */
    private const PAGES = [
        'home'            => ['1.0', 'weekly'],
        'kittens.index'   => ['0.9', 'weekly'],
        'cats.index'      => ['0.8', 'monthly'],
        'breed'           => ['0.7', 'yearly'],
        'gallery'         => ['0.6', 'monthly'],
        'adoption.create' => ['0.8', 'yearly'],
        'faq'             => ['0.7', 'yearly'],
        'contact'         => ['0.7', 'yearly'],
        'legal'           => ['0.3', 'yearly'],
    ];

    public function sitemap(): Response
    {
        $urls = [];

        foreach (self::PAGES as $route => [$priorite, $frequence]) {
            $urls[] = ['loc' => route($route), 'priority' => $priorite, 'changefreq' => $frequence];
        }

        // Seules les fiches reellement publiees : une fiche chaton sans numero
        // ICAD ni numero de portee LOOF renvoie un 404, elle n'a rien a faire
        // dans un sitemap. Cf. Kitten::scopePublies().
        foreach (Cat::publies()->get() as $chat) {
            $urls[] = [
                'loc' => route('cats.show', $chat),
                'lastmod' => $chat->updated_at?->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ];
        }

        foreach (Kitten::publies()->with('litter')->get() as $chaton) {
            $urls[] = [
                'loc' => route('kittens.show', $chaton),
                'lastmod' => $chaton->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
             .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $u) {
            $xml .= '  <url>'."\n".'    <loc>'.e($u['loc']).'</loc>'."\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>'.$u['lastmod'].'</lastmod>'."\n";
            }
            $xml .= '    <changefreq>'.$u['changefreq'].'</changefreq>'."\n"
                 .  '    <priority>'.$u['priority'].'</priority>'."\n"
                 .  '  </url>'."\n";
        }

        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lignes = [
            'User-agent: *',
            // Le back-office n'a rien a faire dans un index de moteur.
            'Disallow: /admin',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ];

        return response(implode("\n", $lignes), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
