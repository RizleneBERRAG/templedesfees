<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Les photos de la galerie n'appartiennent a personne, et le disaient mal.
 *
 * Elles etaient enregistrees avec attachable_type = Litter et attachable_id = 0
 * — donc rattachees a une portee qui n'existe pas. Tant que la visibilite d'une
 * photo ne dependait que de son propre drapeau, personne ne s'en apercevait.
 *
 * Le jour ou l'on a voulu qu'une photo disparaisse avec la fiche qu'elle
 * illustre — un chaton depublie ne doit pas laisser ses images tourner sur
 * l'accueil — ces onze photos se sont evaporees de la galerie et du ruban :
 * leur fiche etait introuvable, donc jamais publiee.
 *
 * Une photo sans rattachement le declare desormais franchement, par un NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->string('attachable_type')->nullable()->change();
            $table->unsignedBigInteger('attachable_id')->nullable()->change();
        });

        DB::table('photos')
            ->where('attachable_id', 0)
            ->update(['attachable_type' => null, 'attachable_id' => null]);
    }

    public function down(): void
    {
        DB::table('photos')
            ->whereNull('attachable_id')
            ->update(['attachable_type' => 'App\Models\Litter', 'attachable_id' => 0]);

        Schema::table('photos', function (Blueprint $table) {
            $table->string('attachable_type')->nullable(false)->change();
            $table->unsignedBigInteger('attachable_id')->nullable(false)->change();
        });
    }
};
