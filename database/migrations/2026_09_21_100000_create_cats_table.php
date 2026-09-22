<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Les reproducteurs et les jeunes sujets gardes a l'elevage. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->string('sexe');                          // male | femelle
            $table->string('role');                          // etalon | reproductrice | observation | retraite
            $table->year('annee_naissance')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('robe')->nullable();              // ex. Brown tabby spotted rosetted
            $table->string('loof_numero')->nullable();
            $table->string('icad_numero')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_principale')->nullable();
            $table->string('photo_secondaire')->nullable();
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('est_publie')->default(true);
            $table->timestamps();

            $table->index(['role', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
