<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Le suivi d'une portee : naissance, vermifuge, identification, vaccins, cession. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('litter_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('litter_id')->constrained()->cascadeOnDelete();
            $table->date('date_evenement')->nullable();
            $table->string('date_libelle')->nullable();      // "Au depart" quand la date est inconnue
            $table->string('libelle');
            $table->string('type')->nullable();              // naissance | vermifuge | identification | vaccin | cession | autre
            $table->boolean('est_fait')->default(false);
            $table->boolean('est_jalon')->default(false);    // le point vert : age legal de cession
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->timestamps();

            $table->index(['litter_id', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('litter_events');
    }
};
