<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * L'acces au back-office.
 *
 * Le compte est cree par le seeder avec un mot de passe aleatoire, affiche une
 * seule fois. Passe cette ligne, personne ne le connait plus — et rejouer le
 * seeder entier pour retrouver l'acces republie des fiches et remet des
 * numeros de demonstration, ce qui n'a rien a voir.
 *
 * Cette commande ne fait que cela : poser le mot de passe du compte de
 * gestion, et dire par ou entrer.
 *
 *   php artisan back-office:acces
 *
 * Le mot de passe se choisit dans .env, qui n'est pas versionne :
 *
 *   BACK_OFFICE_MOT_DE_PASSE=celui-que-vous-voulez
 *
 * Il n'est pas demande en argument de commande : un mot de passe tape dans un
 * terminal reste dans l'historique du shell, en clair, indefiniment.
 */
class BackOfficeAcces extends Command
{
    protected $signature = 'back-office:acces';

    protected $description = 'Pose le mot de passe du back-office depuis .env et rappelle par où entrer';

    public function handle(): int
    {
        $email = config('chatterie.back_office.compte.email');
        $motDePasse = config('chatterie.back_office.compte.mot_de_passe');

        $this->newLine();

        if (blank($motDePasse)) {
            $this->warn('Aucun mot de passe n’est défini.');
            $this->newLine();
            $this->line('  Ajoutez cette ligne dans le fichier .env, à la racine du projet :');
            $this->newLine();
            $this->line('      BACK_OFFICE_MOT_DE_PASSE=celui-que-vous-voulez');
            $this->newLine();
            $this->line('  Puis relancez : php artisan back-office:acces');
            $this->newLine();
            $this->comment('  .env n’est pas versionné : ce mot de passe ne partira pas sur GitHub.');
            $this->newLine();

            return self::FAILURE;
        }

        if (mb_strlen($motDePasse) < 8) {
            $this->error('Ce mot de passe fait moins de huit caractères.');
            $this->line('  C’est la porte d’un espace qui contient des coordonnées de familles.');
            $this->newLine();

            return self::FAILURE;
        }

        $compte = User::updateOrCreate(
            ['email' => $email],
            ['name' => config('chatterie.back_office.compte.nom'), 'password' => Hash::make($motDePasse)],
        );

        $this->info('C’est en place.');
        $this->newLine();
        $this->line('  Adresse     : '.rtrim(config('app.url'), '/').'/admin');
        $this->line('  Identifiant : '.$compte->email);
        $this->line('  Mot de passe: celui de BACK_OFFICE_MOT_DE_PASSE dans .env');
        $this->newLine();

        /*
         * Le panneau n'ouvre qu'aux adresses listees en configuration : un
         * compte juste, un mot de passe juste et une adresse absente de la
         * liste donnent une porte close sans explication. Autant le dire ici.
         */
        $autorisees = config('chatterie.back_office.emails', []);

        if (! in_array($compte->email, $autorisees, true)) {
            $this->warn('  Attention : cette adresse n’est pas dans la liste des entrants autorisés');
            $this->warn('  (config/chatterie.php › back_office.emails). La connexion sera refusée.');
            $this->newLine();
        }

        return self::SUCCESS;
    }
}
