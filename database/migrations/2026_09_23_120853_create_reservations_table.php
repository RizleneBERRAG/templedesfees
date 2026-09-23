<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Les reservations.
 *
 * Une reservation nait d'une decision de l'eleveuse, apres la visite : ce
 * n'est pas un panier qu'un inconnu remplit. Elle porte le chaton, la famille,
 * le montant de l'acompte et un delai. Le chaton n'est immobilise qu'une fois
 * l'acompte encaisse.
 *
 * Le montant est stocke en centimes, en entier. Un acompte en flottant finit
 * toujours par produire un 199,99 la ou on avait tape 200, et on ne discute
 * pas d'un centime avec quelqu'un qui vient de payer.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kitten_id')->constrained()->cascadeOnDelete();

            /*
             * Le dossier d'adoption d'ou vient la reservation, quand il y en a
             * un. Nul si l'eleveuse cree la reservation a la main — un appel
             * telephonique, une famille deja connue.
             *
             * nullOnDelete plutot que cascade : la purge RGPD efface les
             * dossiers au bout de leur duree de conservation, et une
             * reservation payee doit survivre a cette purge. Elle est une piece
             * comptable.
             */
            $table->foreignId('adoption_request_id')->nullable()->constrained()->nullOnDelete();

            $table->string('prenom');
            $table->string('nom');
            $table->string('email');
            $table->string('telephone')->nullable();

            $table->unsignedInteger('acompte_centimes');
            $table->string('devise', 3)->default('EUR');

            /*
             * Le jeton de l'adresse publique. C'est lui qui fait office de
             * clef : la page de paiement ne demande pas de compte, et le lien
             * part par courriel. Quarante caracteres tires au hasard, uniques,
             * et jamais l'identifiant en clair dans l'URL.
             */
            $table->string('jeton', 40)->unique();

            $table->string('statut')->default('en_attente');
            $table->timestamp('expire_le')->nullable();
            $table->timestamp('paye_le')->nullable();

            /*
             * Les references Stripe. On garde la session pour retrouver un
             * paiement dans leur tableau de bord, et l'intention pour pouvoir
             * rembourser sans rien ressaisir.
             */
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_intent')->nullable();

            $table->text('note_interne')->nullable();

            $table->timestamps();

            $table->index(['statut', 'expire_le']);
            $table->index('kitten_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
