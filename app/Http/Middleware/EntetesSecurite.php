<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-tetes de securite.
 *
 * L'audit du site actuel du client lui reproche de n'en avoir aucun. Le site qui
 * le remplace ne peut pas etre dans le meme cas.
 *
 * La politique de contenu (CSP) ne s'applique qu'au site public : le back-office
 * repose sur Alpine et Livewire, qui evaluent des expressions a la volee et
 * demanderaient 'unsafe-eval'. Une CSP qui autorise tout ne protege de rien, et
 * en imposer une trop stricte casserait le panneau. Les autres en-tetes, eux,
 * sont poses partout — ils n'ont aucun effet de bord.
 */
class EntetesSecurite
{
    /** Ce que le navigateur a le droit de charger sur le site public. */
    private const CSP = [
        "default-src 'self'",
        // Les tuiles de la carte viennent de CartoDB ; data: sert aux images inlinees.
        "img-src 'self' data: https://*.basemaps.cartocdn.com",
        // Les vues portent beaucoup d'attributs style= issus de la maquette.
        "style-src 'self' 'unsafe-inline'",
        // Aucun script en ligne cote public, sauf les blocs JSON-LD que le
        // navigateur n'execute pas mais que la CSP couvre malgre tout.
        "script-src 'self' 'unsafe-inline'",
        "font-src 'self'",
        "connect-src 'self'",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'self'",
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $reponse = $next($request);

        // PHP annonce sa version a qui la demande : rien a y gagner, tout a y perdre.
        header_remove('X-Powered-By');
        $reponse->headers->remove('X-Powered-By');

        $reponse->headers->add([
            // Empeche le navigateur de deviner un type MIME, et donc d'executer
            // un fichier televerse comme s'il etait un script.
            'X-Content-Type-Options' => 'nosniff',
            // Le site ne doit pas pouvoir etre affiche dans le cadre d'un tiers.
            'X-Frame-Options' => 'SAMEORIGIN',
            // L'adresse complete n'est transmise qu'aux pages du meme site.
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            // Rien de tout cela n'est utilise : autant le refuser explicitement.
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=()',
        ]);

        // Les points d'entree Livewire servent le back-office : les couvrir n'a
        // pas de sens puisque le panneau lui-meme en est exclu.
        if (! $request->is('admin', 'admin/*', 'livewire/*')) {
            $reponse->headers->set('Content-Security-Policy', implode('; ', self::CSP));
        }

        return $reponse;
    }
}
