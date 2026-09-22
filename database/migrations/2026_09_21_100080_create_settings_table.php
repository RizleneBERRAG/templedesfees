<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reglages de l'elevage en cle/valeur : coordonnees et surtout les mentions
 * legales obligatoires (SIREN, certificat de capacite) reprises dans les fiches.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique();
            $table->text('valeur')->nullable();
            $table->string('libelle')->nullable();
            $table->string('groupe')->default('general');    // general | legal | contact
            $table->boolean('est_obligatoire')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
