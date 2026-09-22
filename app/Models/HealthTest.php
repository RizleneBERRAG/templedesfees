<?php

namespace App\Models;

use App\Enums\HealthTestType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthTest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type'         => HealthTestType::class,
            'date_examen'  => 'date',
        ];
    }

    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class);
    }

    /** Un resultat encore attendu s'affiche en bronze, pas en vert. */
    public function estEnAttente(): bool
    {
        return blank($this->resultat) || str_starts_with($this->resultat, 'À');
    }
}
