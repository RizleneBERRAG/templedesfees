<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Dossier adoptant.
 *
 * RGPD : le consentement est horodate a la creation et la date de purge est
 * calculee a ce moment-la (consentement + 24 mois, cf. la page Mentions legales).
 * Aucun nom d'adoptant n'est jamais expose cote public — voir KittenController.
 */
class AdoptionRequest extends Model
{
    public const MOIS_CONSERVATION = 24;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'consentement_le' => 'datetime',
            'a_purger_le'     => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $demande) {
            $demande->consentement_le ??= now();
            $demande->a_purger_le    ??= $demande->consentement_le->copy()->addMonths(self::MOIS_CONSERVATION);
        });
    }

    public function kitten(): BelongsTo
    {
        return $this->belongsTo(Kitten::class);
    }

    public function scopeAPurger($query)
    {
        return $query->where('a_purger_le', '<=', now());
    }
}
