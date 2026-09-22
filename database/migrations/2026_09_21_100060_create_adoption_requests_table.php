<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dossier adoptant. RGPD : consentement horodate, duree de conservation 24 mois,
 * purge automatique via la commande adoptions:purge.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->foreignId('kitten_id')->nullable()->constrained()->nullOnDelete();
            $table->string('souhait')->nullable();           // texte libre si aucun chaton choisi
            $table->string('logement')->nullable();
            $table->string('autres_animaux')->nullable();
            $table->string('presence')->nullable();
            $table->string('experience')->nullable();
            $table->text('message')->nullable();
            $table->string('statut')->default('nouveau');    // nouveau | en_cours | visite_prevue | accepte | refuse | archive
            $table->text('note_interne')->nullable();
            // datetime et non timestamp : MySQL n'accepte qu'une seule colonne timestamp
            // NOT NULL sans defaut par table, et datetime n'a pas la limite de 2038.
            // Les deux sont renseignees par AdoptionRequest::booted() a la creation.
            $table->dateTime('consentement_le')->nullable();   // preuve du consentement RGPD
            $table->dateTime('a_purger_le')->nullable();       // consentement + 24 mois
            $table->timestamps();

            $table->index(['statut', 'created_at']);
            $table->index('a_purger_le');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
