<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Messages du formulaire de contact — distincts des dossiers adoptants,
 * qui portent bien plus d'informations et se conservent plus longtemps.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('objet');                          // adoption | visite | race | autre
            $table->string('prenom');
            $table->string('nom')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->text('message');
            $table->boolean('est_traite')->default(false);
            $table->dateTime('consentement_le')->nullable();
            $table->dateTime('a_purger_le')->nullable();
            $table->timestamps();

            $table->index(['est_traite', 'created_at']);
            $table->index('a_purger_le');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
