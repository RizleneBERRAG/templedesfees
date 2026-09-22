<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Avis d'adoptants repris de la fiche Google de l'elevage.
 *
 * Seul le prenom est stocke : la page Mentions legales s'engage a ne publier
 * les temoignages que sous le prenom seul et avec accord ecrit. Pas de colonne
 * pour un nom de famille, pour que la regle ne puisse pas etre contournee par
 * distraction.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('prenom', 80);
            $table->unsignedTinyInteger('note');              // 1 a 5
            $table->text('texte');
            $table->date('publie_le')->nullable();            // date de l'avis sur Google
            $table->string('source')->default('google');      // au cas ou d'autres sources s'ajoutent
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('est_publie')->default(true);
            $table->timestamps();

            $table->index(['est_publie', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
