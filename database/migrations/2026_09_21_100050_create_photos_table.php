<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Galerie polymorphe : rattachable a un chat, un chaton ou une portee. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('chemin');
            $table->string('alt')->nullable();
            $table->string('legende')->nullable();
            $table->string('categorie')->nullable();         // chatons | adultes | maison
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('est_couverture')->default(false);
            $table->boolean('est_publiee')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
