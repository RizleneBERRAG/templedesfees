<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un avis d'adoptant, repris de la fiche Google de l'elevage.
 *
 * Aucun nom de famille n'est stocke ni affiche : la page Mentions legales
 * s'engage a ne publier les temoignages que sous le prenom seul et avec
 * accord ecrit. Le lien vers la fiche Google reste affiche a cote des avis
 * pour que le lecteur puisse verifier la source lui-meme.
 */
class Review extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'publie_le'       => 'date',
            'consentement_le' => 'datetime',
            'est_publie'      => 'boolean',
            'note'            => 'integer',
        ];
    }

    /**
     * Du plus recent au plus ancien, puis par prenom a date egale.
     *
     * Il y avait un champ « ordre d'affichage » a la main : deux avis pouvaient
     * porter le meme rang, et le classement devenait alors imprevisible. La date
     * est de toute façon ce qu'attend un lecteur — le temoignage le plus recent
     * en premier — et elle ne demande aucun entretien.
     */
    public function scopePublies($query)
    {
        return $query->where('est_publie', true)
            ->orderByDesc('publie_le')
            ->orderBy('prenom');
    }

    /** Les avis deposes par le site et pas encore relus. */
    public function scopeEnAttente($query)
    {
        return $query->where('est_publie', false)->where('source', 'site');
    }

    /** Les etoiles pleines, pour l'affichage. */
    public function etoiles(): string
    {
        return str_repeat('★', $this->note).str_repeat('☆', 5 - $this->note);
    }
}
