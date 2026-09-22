<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Une portee : le pere, la mere, les dates cles et le numero LOOF de portee. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('litters', function (Blueprint $table) {
            $table->id();
            $table->string('code');                          // "Portee X"
            $table->string('slug')->unique();
            $table->foreignId('pere_id')->nullable()->constrained('cats')->nullOnDelete();
            $table->foreignId('mere_id')->nullable()->constrained('cats')->nullOnDelete();
            $table->date('date_naissance');
            $table->date('date_disponibilite')->nullable();  // naissance + 12 semaines
            $table->string('loof_portee_numero')->nullable();
            $table->unsignedTinyInteger('nb_chatons')->default(0);
            $table->text('description')->nullable();
            $table->string('photo_principale')->nullable();
            $table->boolean('est_publiee')->default(false);
            $table->timestamps();

            $table->index('date_naissance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('litters');
    }
};
