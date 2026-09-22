<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Reglages de l'elevage. Les cles du groupe "legal" marquees est_obligatoire
 * sont celles que la loi impose d'afficher : le site signale celles qui sont vides.
 */
class Setting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['est_obligatoire' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }

    public static function all_cached(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever('settings', fn () => static::query()->get()->keyBy('cle'));
    }

    public static function get(string $cle, ?string $defaut = null): ?string
    {
        $valeur = static::all_cached()->get($cle)?->valeur;

        return filled($valeur) ? $valeur : $defaut;
    }

    /** Les mentions obligatoires encore vides, affichees en "À compléter". */
    public static function obligatoiresManquantes(): \Illuminate\Support\Collection
    {
        return static::all_cached()->filter(fn (self $s) => $s->est_obligatoire && blank($s->valeur));
    }
}
