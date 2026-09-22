<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Photo extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'est_couverture' => 'boolean',
            'est_publiee'    => 'boolean',
        ];
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Volontairement SANS tri, contrairement aux autres scopes publics : deux
     * appelants veulent un ordre aleatoire — la mosaique de l'accueil et le ruban
     * defilant. Un orderBy pose ici passerait devant leur inRandomOrder() et le
     * hasard disparaitrait. Les appelants trient donc eux-memes, et ils le font
     * tous.
     */
    public function scopePubliees($query)
    {
        return $query->where('est_publiee', true);
    }
}
