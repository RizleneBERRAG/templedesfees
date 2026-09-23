<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Les reservations nees de ce dossier.
     *
     * Le lien n'est pas une contrainte : une reservation peut naitre d'un
     * appel, sans dossier. Mais quand le dossier existe, c'est lui qui a
     * fourni le nom, l'adresse et le chaton — et on veut pouvoir le retrouver.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /** Une reservation deja ouverte pour ce dossier, payee ou en attente. */
    public function reservationEnCours(): ?Reservation
    {
        return $this->reservations()
            ->whereIn('statut', ['en_attente', 'payee'])
            ->latest('id')
            ->first();
    }

    public function scopeAPurger($query)
    {
        return $query->where('a_purger_le', '<=', now());
    }
}
