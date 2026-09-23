<?php

namespace App\Models;

use App\Enums\KittenStatus;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
            'expire_le'        => 'datetime',
            'paye_le'          => 'datetime',
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
        $euros = $this->acompte_centimes / 100;

        return number_format($euros, fmod($euros, 1) === 0.0 ? 0 : 2, ',', ' ').' €';
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
