<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Un chaton. Regle metier : la fiche ne peut pas etre publiee tant que
 * le numero ICAD du chaton ET le numero de portee LOOF sont vides
 * (cf. App\Models\Kitten::estPubliable et KittenObserver).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kittens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('litter_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->string('reference')->nullable();         // X-01
            $table->string('sexe');                          // male | femelle
            $table->string('robe')->nullable();
            $table->string('statut')->default('disponible'); // disponible | reserve | adopte
            $table->unsignedInteger('poids_g')->nullable();
            $table->date('poids_releve_le')->nullable();
            $table->string('icad_numero')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_principale')->nullable();
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('est_publie')->default(false);
            $table->timestamps();

            $table->index(['statut', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kittens');
    }
};
