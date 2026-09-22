<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Les avis peuvent desormais etre deposes par les visiteurs.
 *
 * Un avis depose arrive en attente, jamais en ligne : la page Mentions legales
 * s'engage a ne publier un temoignage qu'avec l'accord de son auteur, et rien
 * ne doit pouvoir paraitre sans relecture de l'elevage.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Jamais affiche cote public : sert a recontacter l'auteur si besoin.
            $table->string('email')->nullable()->after('prenom');

            // Horodatage de la case cochee au depot. dateTime et non timestamp :
            // MySQL refuse deux colonnes timestamp NOT NULL dans la meme table.
            $table->dateTime('consentement_le')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['email', 'consentement_le']);
        });
    }
};
