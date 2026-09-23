<?php

namespace App\Console\Commands;

use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Setting;
use Illuminate\Console\Command;

/**
 * Outil de demonstration.
 *
 * Remplit (ou vide) les numeros LOOF, ICAD et les mentions legales obligatoires
 * pour montrer en direct la regle de publication : sans numeros, les fiches
 * chatons restent en brouillon et n'apparaissent pas sur le site.
 *
 *   php artisan demo:numeros            -> remplit et publie
 *   php artisan demo:numeros --reset    -> vide et depublie
 *
 * A supprimer une fois le back-office en place.
 */
class DemoNumeros extends Command
{
    protected $signature = 'demo:numeros {--reset : Vider les numéros et repasser les fiches en brouillon}';

    protected $description = 'Remplit ou vide les numéros LOOF/ICAD de démonstration';

    public function handle(): int
    {
        $reset = $this->option('reset');

        Litter::query()->update([
            'loof_portee_numero' => $reset ? null : 'LOOF-2026-DEMO',
        ]);

        $publies = 0;

        foreach (Kitten::with('litter')->get() as $chaton) {
            $chaton->icad_numero = $reset ? null : '250 269 '.str_pad((string) $chaton->id, 9, '0', STR_PAD_LEFT);

            // Les chatons deja adoptes de la portee archivee restent hors ligne.
            $chaton->est_publie = ! $reset && $chaton->litter?->slug === 'portee-b-2026';
            $chaton->save();

            $publies += $chaton->est_publie ? 1 : 0;
        }

        /*
         * Les mentions obligatoires ne sont PAS remplies ici.
         *
         * Une version precedente y posait « 000 000 000 00000 » et
         * « CCAD-26-DEMO ». C'etait deux fois mauvais : ca se voyait comme un
         * site inacheve, et un numero d'immatriculation invente, publie sur
         * un site, est une fausse mention legale — meme illisible, meme
         * provisoire.
         *
         * Vides, elles s'affichent « A completer » en or sur la page des
         * mentions : c'est le comportement prevu par la charte, c'est honnete,
         * et le tableau de bord les reclame. Devant une cliente, cela se
         * montre plutot que cela se cache.
         */

        Setting::all_cached();

        if ($reset) {
            $this->warn('Numéros vidés. Les fiches chatons sont repassées en brouillon.');
            $this->line('La page /chatons est maintenant vide : c’est la règle légale qui s’applique.');
        } else {
            $this->info("Numéros de démonstration en place. {$publies} fiche(s) chaton publiée(s).");
            $this->line('Rappel : ce sont des numéros factices, à remplacer par les vrais.');
        }

        return self::SUCCESS;
    }
}
