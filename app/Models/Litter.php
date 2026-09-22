<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Litter extends Model
{
    use HasFactory;

    /** Age legal de cession d'un chaton en France. */
    public const SEMAINES_AVANT_CESSION = 12;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_naissance'      => 'date',
            'date_disponibilite'  => 'date',
            'est_publiee'         => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function pere(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'pere_id');
    }

    public function mere(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'mere_id');
    }

    public function kittens(): HasMany
    {
        return $this->hasMany(Kitten::class)->orderBy('ordre');
    }

    public function events(): HasMany
    {
        return $this->hasMany(LitterEvent::class)->orderBy('ordre');
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'attachable')->orderBy('ordre');
    }

    /** Date a partir de laquelle les chatons peuvent legalement partir. */
    public function dateCessionLegale(): ?\Illuminate\Support\Carbon
    {
        return $this->date_naissance?->copy()->addWeeks(self::SEMAINES_AVANT_CESSION);
    }

    public function ageEnSemaines(): ?int
    {
        return $this->date_naissance?->diffInWeeks(now());
    }

    /**
     * La phrase de disponibilite, accordee au temps.
     *
     * « depuis » ne vaut que pour une date passee : tant que l'age legal de
     * cession n'est pas atteint, annoncer un depart deja possible est faux.
     * C'est exactement le reproche fait au site actuel du client, qui affiche
     * encore des chatons de mars 2025 comme une actualite — le nouveau site ne
     * peut pas se permettre le meme decalage.
     */
    public function phraseDisponibilite(): ?string
    {
        if (! $this->date_disponibilite) {
            return null;
        }

        $date = $this->date_disponibilite->translatedFormat('j F Y');

        return $this->date_disponibilite->isFuture()
            ? "départs à partir du {$date}"
            : "départs possibles depuis le {$date}";
    }

    public function scopePubliees($query)
    {
        return $query->where('est_publiee', true);
    }

    public function scopeEnCours($query)
    {
        return $query->whereHas('kittens', fn ($q) => $q->where('statut', '!=', 'adopte'));
    }
}
