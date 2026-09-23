<?php

namespace App\Models;

use App\Enums\KittenStatus;
use App\Enums\ReservationStatus;
use App\Services\Caisse;
use App\Support\Monnaie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Une reservation de chaton.
 *
 * Elle porte le chaton, la famille, le montant de l'acompte et un delai. Toute
 * la regle metier tient en une phrase : le chaton n'est immobilise que par un
 * acompte encaisse. Une reservation en attente ne reserve rien — c'est ce que
 * le site promet, et c'est ce que le code applique.
 *
 * Le passage d'un statut a l'autre est la seule porte d'entree : les methodes
 * payer(), annuler(), expirer() et rembourser() s'occupent aussi du chaton,
 * pour qu'il soit impossible d'avoir une reservation payee sur un chaton
 * encore annonce disponible.
 */
class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*
     * La base pose ces valeurs par defaut a l'insertion, mais l'objet en
     * memoire ne les connait pas avant d'etre relu. Une reservation qu'on
     * vient de creer doit deja savoir qu'elle est en attente.
     */
    protected $attributes = [
        'statut' => 'en_attente',
        'devise' => 'EUR',
    ];

    protected function casts(): array
    {
        return [
            'statut'           => ReservationStatus::class,
            'acompte_centimes' => 'integer',
            'prix_centimes'    => 'integer',
            'expire_le'        => 'datetime',
            'depart_prevu_le'  => 'date',
            'paye_le'          => 'datetime',
            'facture_le'       => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Le jeton de l'adresse publique est tire a la creation et ne change
        // plus : un lien deja parti par courriel doit continuer de marcher.
        static::creating(function (self $reservation) {
            $reservation->jeton ??= Str::random(40);
        });
    }

    public function kitten(): BelongsTo
    {
        return $this->belongsTo(Kitten::class);
    }

    public function adoptionRequest(): BelongsTo
    {
        return $this->belongsTo(AdoptionRequest::class);
    }

    /* ── lecture ─────────────────────────────────────────────────── */

    public function nomComplet(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /** Le montant en euros, tel qu'on l'ecrit : « 300 € » ou « 299,50 € ». */
    public function acompteFormate(): string
    {
        return Monnaie::euros($this->acompte_centimes);
    }

    public function prixFormate(): string
    {
        return Monnaie::euros($this->prix_centimes);
    }

    /**
     * Ce qui restera a regler le jour du depart.
     *
     * Nul tant qu'aucun prix n'a ete convenu : mieux vaut un tiret sur le
     * contrat qu'un solde calcule a partir d'un prix qu'on a oublie de saisir.
     */
    public function soldeCentimes(): ?int
    {
        return $this->prix_centimes === null
            ? null
            : max(0, $this->prix_centimes - $this->acompte_centimes);
    }

    public function soldeFormate(): string
    {
        return Monnaie::euros($this->soldeCentimes());
    }

    /**
     * La reference du dossier, celle qu'on cite au telephone.
     *
     * Elle n'a rien a voir avec le numero de facture : elle existe des la
     * creation, elle sert a se retrouver, et elle n'engage rien.
     */
    public function reference(): string
    {
        return 'R-'.$this->created_at?->format('Y').'-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** La date de depart ecrite au contrat : celle qu'on a fixee, sinon celle de la portee. */
    public function departPrevu(): ?\Illuminate\Support\Carbon
    {
        return $this->depart_prevu_le ?? $this->kitten?->litter?->date_disponibilite;
    }

    public function aUneFacture(): bool
    {
        return filled($this->facture_numero);
    }

    /** L'adresse publique ou la famille vient payer. */
    public function lienPublic(): string
    {
        return route('reservation.montrer', ['jeton' => $this->jeton]);
    }

    /**
     * Le delai est-il passe ?
     *
     * On ne se fie pas au statut seul : une reservation peut avoir depasse son
     * delai sans que la commande de menage soit encore passee. La page
     * publique doit refuser le paiement des la seconde d'apres.
     */
    public function estPerimee(): bool
    {
        return $this->statut->attendUnPaiement()
            && $this->expire_le !== null
            && $this->expire_le->isPast();
    }

    public function peutEtrePayee(): bool
    {
        return $this->statut->attendUnPaiement() && ! $this->estPerimee();
    }

    /* ── ecriture ────────────────────────────────────────────────── */

    /**
     * L'acompte est arrive.
     *
     * Appelee par le webhook Stripe, donc potentiellement plusieurs fois pour
     * le meme paiement : Stripe rejoue ses notifications tant qu'il n'a pas
     * recu un accuse. La methode est donc sans effet la deuxieme fois.
     */
    public function payer(?string $paymentIntent = null): void
    {
        if ($this->statut === ReservationStatus::Payee) {
            return;
        }

        $this->forceFill([
            'statut'                => ReservationStatus::Payee,
            'paye_le'               => now(),
            'stripe_payment_intent' => $paymentIntent ?? $this->stripe_payment_intent,
        ])->save();

        $this->bloquerLeChaton();
        $this->emettreLaFacture();
    }

    /**
     * Numeroter la facture d'acompte.
     *
     * Le numero est alloue a l'encaissement, jamais a la creation : une facture
     * numerotee pour une reservation qui n'a jamais ete payee laisserait un
     * trou dans la suite, et une numerotation a trous est precisement ce qu'un
     * controle ne veut pas voir.
     *
     * La suite est chronologique, continue, remise a un chaque annee :
     * 2026-0001, 2026-0002. L'allocation se fait dans une transaction, avec le
     * verrou : deux acomptes encaisses dans la meme seconde — un virement
     * enregistre a la main pendant qu'un paiement en ligne arrive — ne doivent
     * pas repartir avec le meme numero.
     *
     * Sans effet si la facture existe deja : le webhook Stripe rejoue ses
     * notifications, et une facture ne se renumerote pas.
     *
     * Les acomptes simules en mode demonstration prennent un prefixe a eux.
     * Sans cela, montrer le parcours a la cliente consommerait les premiers
     * numeros de l'annee, et les effacer ensuite laisserait le trou que toute
     * cette methode cherche a eviter.
     */
    public function emettreLaFacture(): void
    {
        if ($this->aUneFacture()) {
            return;
        }

        $serie = Caisse::enDemonstration() ? 'DEMO' : now()->format('Y');

        DB::transaction(function () use ($serie) {
            $dernier = self::query()
                ->where('facture_numero', 'like', $serie.'-%')
                ->lockForUpdate()
                ->max('facture_numero');

            $rang = $dernier ? ((int) substr($dernier, strlen($serie) + 1)) + 1 : 1;

            $this->forceFill([
                'facture_numero' => $serie.'-'.str_pad((string) $rang, 4, '0', STR_PAD_LEFT),
                'facture_le'     => now(),
            ])->save();
        });
    }

    /** Une facture de demonstration, qui ne compte dans aucune comptabilite. */
    public function factureEstFictive(): bool
    {
        return str_starts_with((string) $this->facture_numero, 'DEMO-');
    }

    public function annuler(?string $note = null): void
    {
        $this->forceFill([
            'statut'       => ReservationStatus::Annulee,
            'note_interne' => $note ?? $this->note_interne,
        ])->save();

        $this->libererLeChaton();
    }

    public function expirer(): void
    {
        if (! $this->statut->attendUnPaiement()) {
            return;
        }

        $this->forceFill(['statut' => ReservationStatus::Expiree])->save();
        $this->libererLeChaton();
    }

    public function rembourser(?string $note = null): void
    {
        $this->forceFill([
            'statut'       => ReservationStatus::Remboursee,
            'note_interne' => $note ?? $this->note_interne,
        ])->save();

        $this->libererLeChaton();
    }

    /* ── le chaton suit ──────────────────────────────────────────── */

    /**
     * Le chaton, relu en base.
     *
     * On ne se sert pas de la relation déjà chargée : elle date du moment où
     * on l'a lue, et entre-temps le chaton a pu partir. Un remboursement
     * remettait alors en vente un chaton déjà adopté.
     */
    private function chatonFrais(): ?Kitten
    {
        return $this->kitten_id ? Kitten::find($this->kitten_id) : null;
    }

    private function bloquerLeChaton(): void
    {
        $chaton = $this->chatonFrais();

        if ($chaton && $chaton->statut === KittenStatus::Disponible) {
            $chaton->forceFill(['statut' => KittenStatus::Reserve])->save();
        }
    }

    /**
     * Rendre le chaton.
     *
     * On ne le repasse a « disponible » que s'il n'est pas tenu par une autre
     * reservation payee — deux acomptes sur le meme chaton ne devraient pas
     * arriver, mais un remboursement ne doit pas liberer ce qu'une autre
     * famille a paye. Et on ne touche jamais a un chaton deja parti.
     */
    private function libererLeChaton(): void
    {
        $chaton = $this->chatonFrais();

        if (! $chaton || $chaton->statut === KittenStatus::Adopte) {
            return;
        }

        $autre = self::where('kitten_id', $chaton->id)
            ->where('id', '!=', $this->id)
            ->where('statut', ReservationStatus::Payee)
            ->exists();

        if (! $autre && $chaton->statut === KittenStatus::Reserve) {
            $chaton->forceFill(['statut' => KittenStatus::Disponible])->save();
        }
    }
}
