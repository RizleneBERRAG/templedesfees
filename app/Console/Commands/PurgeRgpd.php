<?php

namespace App\Console\Commands;

use App\Models\AdoptionRequest;
use App\Models\ContactMessage;
use Illuminate\Console\Command;

/**
 * Purge RGPD. Les dossiers adoptants sont conserves 24 mois apres le consentement,
 * comme annonce sur la page Mentions legales. A planifier une fois par jour.
 */
class PurgeRgpd extends Command
{
    protected $signature = 'rgpd:purge {--dry-run : Afficher ce qui serait supprimé sans rien supprimer}';

    protected $description = 'Supprime les dossiers adoptants et messages dont la durée de conservation est écoulée';

    public function handle(): int
    {
        $dossiers = AdoptionRequest::aPurger();
        $messages = ContactMessage::aPurger();
        $nombre   = $dossiers->count() + $messages->count();

        if ($nombre === 0) {
            $this->info('Rien à purger.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn("{$nombre} enregistrement(s) seraient supprimés.");

            return self::SUCCESS;
        }

        $dossiers->delete();
        $messages->delete();
        $this->info("{$nombre} enregistrement(s) supprimé(s).");

        return self::SUCCESS;
    }
}
