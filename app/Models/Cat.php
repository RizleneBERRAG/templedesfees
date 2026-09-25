<?php

namespace App\Models;

use App\Enums\CatRole;
use App\Enums\HealthTestType;
use App\Models\Concerns\ASexe;
use App\Models\Concerns\AUneGalerie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Cat extends Model
{
    use ASexe;
    use AUneGalerie;
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'role'            => CatRole::class,
            'date_naissance'  => 'date',
            'est_publie'      => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function healthTests(): HasMany
    {
        return $this->hasMany(HealthTest::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'attachable')->orderBy('ordre');
    }

    public function altParDefaut(): string
    {
        return $this->nom.', Maine Coon '.\Illuminate\Support\Str::lower($this->robe);
    }

    public function porteesCommePere(): HasMany
    {
        return $this->hasMany(Litter::class, 'pere_id');
    }

    public function porteesCommeMere(): HasMany
    {
        return $this->hasMany(Litter::class, 'mere_id');
    }

    /** Toutes les portees du chat, quel que soit son role. */
    public function portees()
    {
        return Litter::query()
            ->where('pere_id', $this->id)
            ->orWhere('mere_id', $this->id)
            ->orderByDesc('date_naissance');
    }

    /**
     * Le bilan sante est complet quand chaque depistage requis a un resultat.
     * Tant qu'il ne l'est pas, le chat ne devrait pas etre mis a la reproduction.
     */
    public function bilanSanteComplet(): bool
    {
        $renseignes = $this->healthTests
            ->filter(fn (HealthTest $t) => filled($t->resultat) && ! str_starts_with($t->resultat, 'À'))
            ->pluck('type')
            ->map(fn ($t) => $t instanceof HealthTestType ? $t->value : $t)
            ->all();

        foreach (HealthTestType::requisReproduction() as $requis) {
            if (! in_array($requis->value, $renseignes, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * L'ordre fait partie du scope, il n'est pas laisse a l'appelant.
     *
     * Quatre endroits appelaient ce scope et trois seulement pensaient a trier :
     * le champ « ordre d'affichage » du back-office n'agissait donc pas partout,
     * et l'ordre paraissait correct par coincidence — les identifiants suivaient
     * l'ordre voulu. Le jour ou l'eleveuse reordonne une fiche, rien n'aurait
     * bouge. Le classement appartient au modele, comme la regle de publication.
     */
    public function scopePublies($query)
    {
        return $query->where('est_publie', true)
            ->orderBy('ordre')
            ->orderBy('nom');
    }

    public function scopeReproducteurs($query)
    {
        return $query->whereIn('role', [CatRole::Etalon->value, CatRole::Reproductrice->value]);
    }
}
