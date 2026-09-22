<?php

namespace App\Console\Commands;

use App\Models\Litter;
use App\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Enregistre en base les images presentes dans public/images/cats.
 * Passerelle en attendant l'upload depuis le back-office : on depose les
 * fichiers, on lance la commande, la galerie se remplit.
 */
class SyncPhotos extends Command
{
    protected $signature = 'photos:sync {--legendes : Rafraîchir aussi les légendes connues des photos déjà en base}';

    protected $description = 'Enregistre les images de public/images/cats dans la galerie';

    /** Legendes connues ; les autres sont deduites du nom de fichier. */
    private const LEGENDES = [
        'hero-duo'      => ['Nos Maine Coon à la maison', 'maison'],
        'hero-uanna'    => ['Uanna — brown tabby rosetted', 'adultes'],
        'uanna'         => ['Uanna — contraste et glitter', 'adultes'],
        'uzumaki'       => ['Uzumaki — portrait', 'adultes'],
        'uzu-harnais'   => ['Uzumaki au jardin', 'adultes'],
        'couple'        => ['Uzumaki et Uanna', 'adultes'],
        'wild'          => ['Après-midi dans la végétation', 'adultes'],
        'xena'          => ['Xena — brown tabby spotted', 'adultes'],
        'xena2'         => ['Sieste de fin d’après-midi', 'maison'],
        'wendy'         => ['Wendy', 'adultes'],
        'ambiance'      => ['Fin de journée à la maison', 'maison'],
        'portee'        => ['Portée W — mars 2025', 'chatons'],
        'chatons-pile'  => ['Fratrie au repos', 'chatons'],
        'banner-petits' => ['Quatre chatons de la portée, tous en alerte', 'chatons'],
    ];

    public function handle(): int
    {
        $dossier = public_path('images/cats');
        $fichiers = glob($dossier.'/*.{webp,jpg,jpeg,png}', GLOB_BRACE) ?: [];

        if ($fichiers === []) {
            $this->warn('Aucune image trouvée dans public/images/cats.');

            return self::FAILURE;
        }

        $ordre = (int) Photo::max('ordre');
        $crees = 0;
        $majs  = 0;

        foreach ($fichiers as $fichier) {
            $base    = pathinfo($fichier, PATHINFO_FILENAME);
            $chemin  = 'images/cats/'.basename($fichier);

            [$legende, $categorie] = self::LEGENDES[$base] ?? [
                Str::of($base)->replace(['-', '_'], ' ')->ucfirst()->toString(),
                Str::startsWith($base, ['k', 'g']) ? 'chatons' : 'adultes',
            ];

            $photo = Photo::firstOrNew([
                'attachable_type' => Litter::class,
                'attachable_id'   => 0,
                'chemin'          => $chemin,
            ]);

            if (! $photo->exists) {
                $photo->fill([
                    'alt'       => $legende,
                    'legende'   => $legende,
                    'categorie' => $categorie,
                    'ordre'     => ++$ordre,
                ])->save();
                $crees++;
                continue;
            }

            // --legendes : on réaligne les libellés connus, sans toucher au reste.
            if ($this->option('legendes') && isset(self::LEGENDES[$base])) {
                $photo->update(['alt' => $legende, 'legende' => $legende, 'categorie' => $categorie]);
                $majs++;
            }
        }

        $this->info("{$crees} photo(s) ajoutée(s), {$majs} mise(s) à jour. Galerie : ".Photo::count().' au total.');

        return self::SUCCESS;
    }
}
