<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['est_publiee' => 'boolean'];
    }

    public function scopePubliees($query)
    {
        return $query->where('est_publiee', true)->orderBy('ordre');
    }
}
