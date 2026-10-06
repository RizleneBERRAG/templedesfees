<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Le facteur.
 *
 * Tous les courriels du site passent par ici, et pour une seule raison : un
 * envoi qui echoue ne doit jamais faire echouer le geste qui l'a declenche.
 *
 * Une famille qui remplit un dossier d'adoption a droit a sa page « merci »
 * meme si le serveur de courriel est en panne, si l'hebergeur a coupe le port
 * 587 ce matin-la, ou si le mot de passe SMTP a expire. Son dossier est deja
 * enregistre a ce moment-la : le perdre parce qu'un courriel n'est pas parti
 * serait perdre une adoption pour une panne qui ne la regarde pas.
 *
 * L'echec part donc dans les journaux, et nulle part ailleurs. L'eleveur, lui,
 * retrouve la demande dans son espace de gestion : c'est la que vit la verite,
 * le courriel n'est qu'un rappel.
 */
class Facteur
{
    /**
     * Porter une lettre. Rend true si elle est partie.
     *
     * @param  string|array<int, string>  $destinataires
     */
    public static function porter(string|array $destinataires, Mailable $lettre): bool
    {
        $destinataires = array_values(array_filter((array) $destinataires));

        if ($destinataires === []) {
            return false;
        }

        try {
            Mail::to($destinataires)->send($lettre);

            return true;
        } catch (Throwable $e) {
            Log::error('Courriel non parti', [
                'destinataires' => $destinataires,
                'lettre'        => $lettre::class,
                'sujet'         => method_exists($lettre, 'envelope') ? $lettre->envelope()->subject : null,
                'raison'        => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Les adresses de l'elevage, celles qui recoivent les notifications.
     *
     * @return array<int, string>
     */
    public static function elevage(): array
    {
        return array_values(array_filter(
            (array) config('chatterie.back_office.emails', [])
        ));
    }
}
