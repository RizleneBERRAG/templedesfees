<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\Caisse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * La page de reservation.
 *
 * Elle n'est pas dans le menu et ne s'indexe pas : on y arrive par un lien
 * envoye a une famille precise, apres la visite. Le jeton de quarante
 * caracteres en tient lieu de clef — il n'y a pas de compte a creer pour
 * verser un acompte, et en demander un ferait perdre la moitie des familles.
 */
class ReservationController extends Controller
{
    /** La page ou la famille voit ce qu'elle paie, et pourquoi. */
    public function montrer(string $jeton)
    {
        $reservation = $this->trouver($jeton);

        return view('pages.reservation.montrer', [
            'reservation' => $reservation,
            'chaton'      => $reservation->kitten,
        ]);
    }

    /**
     * Le depart vers le paiement.
     *
     * Rien n'est encaisse ici : on ouvre une session chez Stripe et on y
     * envoie la famille. Le formulaire de carte est sur leur domaine, jamais
     * sur le notre.
     */
    public function payer(Request $request, string $jeton): RedirectResponse
    {
        $reservation = $this->trouver($jeton);

        if (! $reservation->peutEtrePayee()) {
            return back()->with('erreur', 'Cette réservation n’est plus en attente de paiement.');
        }

        $request->validate(
            ['conditions' => ['accepted']],
            ['conditions.accepted' => 'Merci de confirmer que vous avez lu les conditions de l’acompte.']
        );

        /*
         * Le mode demonstration. Il n'existe que tant qu'aucune clef Stripe
         * n'est renseignee, et sert a montrer le parcours complet a l'eleveuse
         * avant l'ouverture du compte. La page le dit en toutes lettres.
         */
        if (Caisse::enDemonstration()) {
            $reservation->payer();

            return redirect()->route('reservation.merci', ['jeton' => $jeton]);
        }

        if (! Caisse::estOuverte()) {
            return back()->with('erreur',
                'Le paiement en ligne n’est pas encore ouvert. Appelez-nous, nous réglerons cela autrement.');
        }

        return redirect()->away(Caisse::ouvrirLaSession($reservation));
    }

    /** Le retour de Stripe, une fois la carte passee. */
    public function merci(string $jeton)
    {
        $reservation = $this->trouver($jeton);

        return view('pages.reservation.merci', [
            'reservation' => $reservation,
            'chaton'      => $reservation->kitten,
        ]);
    }

    /**
     * La notification de Stripe.
     *
     * C'est elle qui fait foi, et non le retour du navigateur : une famille
     * qui ferme son onglet juste apres avoir paye ne doit pas perdre sa
     * reservation. Stripe rejoue la notification jusqu'a recevoir un 200, d'ou
     * l'importance que payer() soit sans effet la deuxieme fois.
     */
    public function webhook(Request $request): Response
    {
        try {
            Caisse::lireLaNotification(
                $request->getContent(),
                $request->header('Stripe-Signature', '')
            );
        } catch (SignatureVerificationException $e) {
            // Signature invalide : soit une mauvaise clef, soit quelqu'un qui
            // frappe a la porte. On refuse sans en dire davantage.
            Log::warning('Notification de paiement refusée : signature invalide.');

            return response('Signature invalide', 400);
        } catch (\Throwable $e) {
            /*
             * On accuse reception malgre l'erreur : un 500 ferait rejouer la
             * notification en boucle sans que cela corrige quoi que ce soit.
             * L'erreur est tracee, l'eleveuse voit le paiement chez Stripe, et
             * la reservation se rattrape a la main.
             */
            Log::error('Notification de paiement non traitée : '.$e->getMessage());

            return response('Reçu', 200);
        }

        return response('Reçu', 200);
    }

    private function trouver(string $jeton): Reservation
    {
        return Reservation::with('kitten.litter')
            ->where('jeton', $jeton)
            ->firstOrFail();
    }
}
