<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le prix d'un chaton.
 *
 * Il ne s'affiche nulle part sur le site : la chatterie n'annonce pas ses
 * tarifs en vitrine, et ce n'est pas a une refonte d'en decider. Il sert aux
 * documents — le contrat de reservation et la facture d'acompte ont besoin du
 * prix convenu pour ecrire le solde qui restera du au depart.
 *
 * En centimes, en entier, comme l'acompte : un prix en flottant finit toujours
 * par produire un 1499,99 la ou on avait tape 1500.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kittens', function (Blueprint $table) {
            $table->unsignedInteger('prix_centimes')->nullable()->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('kittens', function (Blueprint $table) {
            $table->dropColumn('prix_centimes');
        });
    }
};
