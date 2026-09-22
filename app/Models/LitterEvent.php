<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LitterEvent extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_evenement' => 'date',
            'est_fait'       => 'boolean',
            'est_jalon'      => 'boolean',
        ];
    }

    public function litter(): BelongsTo
    {
        return $this->belongsTo(Litter::class);
    }

    /** Date affichee : la date reelle, sinon le libelle libre ("Au départ"). */
    public function quand(): string
    {
        return $this->date_evenement
            ? $this->date_evenement->translatedFormat('j F Y')
            : (string) $this->date_libelle;
    }
}
