<?php

namespace App\Models;

use App\Enums\KittenStatus;
use App\Models\Concerns\AUneGalerie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Un chaton de l'elevage.
 *
 * REGLE METIER CENTRALE
 * ---------------------
 * L'article L214-8-1 du code rural impose que toute annonce de cession d'un chat
 * affiche le numero d'identification de l'animal et le numero de portee LOOF.
 * Une fiche chaton ne peut donc pas etre publiee tant que ces deux numeros sont vides :
 * elle reste en brouillon. La regle est appliquee a trois niveaux —
 *   1. estPubliable() ici, la regle exprimee en PHP ;
 *   2. KittenObserver qui repasse est_publie a false a chaque enregistrement ;
 *   3. le scope publies(), qui revalide la condition en SQL a chaque lecture
 *      publique. C'est lui qui rattrape ce que l'observer ne peut pas voir :
 *      les mises a jour de masse et un numero de portee vide apres coup.
 * Ne pas contourner : c'est la raison d'etre de ce champ.
 */
class Kitten extends Model
{
    use AUneGalerie;
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statut'          => KittenStatus::class,
            'poids_releve_le' => 'date',
            'est_publie'      => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function litter(): BelongsTo
    {
        return $this->belongsTo(Litter::class);
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'attachable')->orderBy('ordre');
    }

    public function altParDefaut(): string
    {
        return $this->nom.', chaton Maine Coon '.\Illuminate\Support\Str::lower($this->robe);
    }

    public function pere(): ?Cat
    {
        return $this->litter?->pere;
    }

    public function mere(): ?Cat
    {
        return $this->litter?->mere;
    }

    /** Les mentions legales manquantes, pour les afficher telles quelles dans le back-office. */
    public function mentionsManquantes(): array
    {
        $manquantes = [];

        if (blank($this->icad_numero)) {
            $manquantes[] = "numéro d'identification ICAD du chaton";
        }

        if (blank($this->litter?->loof_portee_numero)) {
            $manquantes[] = 'numéro de portée LOOF';
        }

        return $manquantes;
    }

    public function estPubliable(): bool
    {
        return $this->mentionsManquantes() === [];
    }

    public function estDisponible(): bool
    {
        return $this->statut === KittenStatus::Disponible;
    }

    public function ageEnSemaines(): ?int
    {
        return $this->litter?->ageEnSemaines();
    }

    /** Le poids formate a la francaise : 1 480 g. */
    public function poidsFormate(): ?string
    {
        return $this->poids_g ? number_format($this->poids_g, 0, ',', ' ').' g' : null;
    }

    /**
     * Les fiches publiees ET reellement publiables.
     *
     * Le drapeau est_publie ne suffit pas : il est maintenu par KittenObserver,
     * qui n'ecoute que les enregistrements Eloquent. Une mise a jour de masse
     * — Kitten::query()->update(...), une action groupee du back-office —
     * le contourne, et vider loof_portee_numero cote Litter ne repasse aucun
     * chaton en brouillon. La condition legale est donc revalidee ici, a la
     * lecture : le drapeau ne peut plus mentir, quel que soit le chemin
     * d'ecriture qui l'a pose.
     */
    public function scopePublies($query)
    {
        return $query->publiables()
            ->where('est_publie', true)
            ->orderBy('ordre')
            ->orderBy('nom');
    }

    /**
     * La seule condition legale, sans le drapeau : le miroir SQL de estPubliable().
     * Sert aussi au back-office pour lister les fiches pretes a etre publiees.
     * Les deux doivent rester d'accord — c'est ce que verifie KittenPublicationTest.
     */
    public function scopePubliables($query)
    {
        return $query
            ->whereNotNull('icad_numero')
            ->where('icad_numero', '<>', '')
            ->whereHas('litter', fn ($q) => $q
                ->whereNotNull('loof_portee_numero')
                ->where('loof_portee_numero', '<>', ''));
    }

    public function scopeDisponibles($query)
    {
        return $query->where('statut', KittenStatus::Disponible->value);
    }

    public function scopeStatut($query, string $statut)
    {
        return $query->where('statut', $statut);
    }
}
