<?php

namespace App\Models;

use App\Enums\KittenStatus;
use App\Models\Concerns\ASexe;
use App\Models\Concerns\AUneGalerie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

/**
 * Un chaton de l'elevage.
 *
 * LES MENTIONS OBLIGATOIRES
 * -------------------------
 * L'article L214-8-1 du code rural impose que toute annonce de cession d'un chat
 * affiche le numero d'identification de l'animal et le numero de portee LOOF.
 *
 * La fiche refusait donc de se publier tant que les deux manquaient. C'etait
 * trop tot : on ne puce pas un nouveau-ne, et le numero de portee met des
 * semaines a revenir du LOOF. Les chatons restaient invisibles precisement
 * pendant les semaines ou les familles se decident.
 *
 * Les numeros ne bloquent donc plus l'affichage. Ils restent reclames, mais la
 * ou ils se voient : mentionsManquantes() les liste, la fiche publique affiche
 * « identification en cours » a leur place, et le tableau de bord les redemande
 * a chaque ouverture tant qu'ils sont vides. Rien n'est cache, et rien n'est
 * invente.
 */
class Kitten extends Model
{
    /** La longueur au-dela de laquelle Google tronque le resume affiche. */
    private const LONGUEUR_RESUME = 155;

    use ASexe;
    use AUneGalerie;
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statut'          => KittenStatus::class,
            'prix_centimes'   => 'integer',
            'poids_releve_le' => 'date',
            'est_publie'      => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Le prix convenu, tel qu'on l'ecrit.
     *
     * Il ne parait sur aucune page du site : la chatterie n'affiche pas ses
     * tarifs en vitrine. Il sert au contrat de reservation et a la facture,
     * qui ont besoin du prix pour ecrire le solde restant du au depart.
     */
    public function prixFormate(): string
    {
        return \App\Support\Monnaie::euros($this->prix_centimes);
    }

    /**
     * Une facture emise interdit la suppression de la fiche.
     *
     * Les cles etrangeres sont posees en cascade : effacer un chaton efface
     * ses reservations, et effacer une portee efface ses chatons — donc leurs
     * reservations aussi. C'est le bon comportement pour une fiche saisie par
     * erreur. C'en est un tres mauvais pour un acompte encaisse : la ligne
     * porte un numero de facture, une somme recue et un contrat.
     *
     * Le critere est le numero de facture et non le statut : c'est lui qui
     * engage la comptabilite, et il n'est emis qu'a l'encaissement. Une
     * reservation en attente, elle, ne protege rien et part avec la fiche.
     */
    public function aUneReservationEncaissee(): bool
    {
        return $this->reservations()->whereNotNull('facture_numero')->exists();
    }

    public function peutEtreSupprime(): bool
    {
        return ! $this->aUneReservationEncaissee();
    }

    /** Les reservations portant sur ce chaton, payees ou non. */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function litter(): BelongsTo
    {
        return $this->belongsTo(Litter::class);
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    /**
     * Les vrais freres et soeurs : memes pere ET meme mere.
     *
     * Ce n'est pas « les chatons de la meme portee », meme si aujourd'hui les
     * deux reviennent au meme. Un couple peut avoir une seconde portee, et ses
     * chatons restent freres et soeurs d'une annee sur l'autre.
     *
     * Et ce n'est surtout pas « les chatons de l'elevage ». Halunke est le pere
     * de deux portees, l'une avec Tika, l'autre avec A'Neora : ces chatons-la
     * sont demi-freres. Les ranger sous le meme mot serait faux, et sur une
     * fiche d'elevage un lien de parente faux ne se rattrape pas.
     */
    public function fratrie(): Builder
    {
        $portee = $this->litter;

        /* Une portee sans parents connus n'etablit aucun lien : on prefere une
           fratrie vide a une fratrie fausse. */
        if (! $portee?->pere_id || ! $portee?->mere_id) {
            return static::query()->whereRaw('1 = 0');
        }

        return static::query()
            ->whereKeyNot($this->getKey())
            ->whereHas('litter', fn ($q) => $q
                ->where('pere_id', $portee->pere_id)
                ->where('mere_id', $portee->mere_id))
            ->orderBy('ordre')
            ->orderBy('nom');
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

    /** Les deux mentions legales sont-elles renseignees ? */
    public function mentionsCompletes(): bool
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
        return $query->where('est_publie', true)
            ->orderBy('ordre')
            ->orderBy('nom');
    }

    /**
     * Le miroir SQL de mentionsCompletes(). Il ne filtre plus l'affichage : il
     * sert au back-office a trier les fiches dont les numeros sont arrives de
     * celles qui les attendent encore. Les deux doivent rester d'accord — c'est
     * ce que verifie KittenPublicationTest.
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

    /**
     * Le resume qui part dans la balise description et dans les partages.
     *
     * La fiche porte un champ Description, mais il reste vide tant que
     * l'eleveur ne l'a pas ecrit — et il l'etait pour les dix chatons mis en
     * ligne le 5 octobre 2026. Une page sans description laisse Google
     * composer le sien avec des bouts de la page, et un partage sur Facebook
     * arrive sans une ligne de texte.
     *
     * On compose donc un resume avec ce que la fiche sait deja. Rien n'y est
     * affirme qui ne soit en base : ni le LOOF ni l'identification n'y
     * figurent, puisqu'ils peuvent manquer. Les parents sont ecrits
     * « X × Y » plutot que « ne de » : cela evite d'accorder un participe
     * au sexe du chaton, et c'est la forme deja employee sur la fiche.
     */
    public function resumePourLesMoteurs(): string
    {
        $ecrit = trim(strip_tags((string) $this->description));

        if ($ecrit !== '') {
            return Str::limit($ecrit, self::LONGUEUR_RESUME);
        }

        /*
         * Les phrases sont rangees par ce qu'elles rapportent, et ajoutees
         * seulement si elles tiennent EN ENTIER : une description coupee au
         * milieu d'un nom de lieu dessert la page qu'elle devait servir.
         *
         * Le lieu passe donc avant les parents. Une famille cherche « chaton
         * maine coon drome » bien plus souvent que le nom d'un etalon, et les
         * noms d'elevage allemands mangent a eux seuls la moitie du budget.
         */
        $phrases = array_filter([
            rtrim(sprintf(
                '%s, chaton Maine Coon %s %s',
                $this->nom,
                mb_strtolower($this->sexeLibelle()),
                mb_strtolower((string) $this->robe),
            )).'.',

            sprintf(
                'Élevage familial à %s, départ à %d semaines au plus tôt.',
                Setting::get('elevage.ville', 'Lapeyrouse-Mornay'),
                Litter::SEMAINES_AVANT_CESSION,
            ),

            ($parents = array_filter([$this->litter?->pere?->nom, $this->litter?->mere?->nom]))
                ? 'Parents : '.implode(' × ', $parents).'.'
                : null,
        ]);

        $resume = '';

        foreach ($phrases as $phrase) {
            $essai = $resume === '' ? $phrase : $resume.' '.$phrase;

            if (mb_strlen($essai) > self::LONGUEUR_RESUME) {
                break;
            }

            $resume = $essai;
        }

        return $resume;
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
