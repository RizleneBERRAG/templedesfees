<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le classement manuel des avis est remplace par un classement par date puis
 * par prenom. Deux avis pouvaient porter le meme rang, et l'ordre devenait
 * alors imprevisible — c'est ce qui donnait l'impression que le champ ne
 * servait a rien. La colonne n'ayant plus de lecteur, elle s'en va.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['est_publie', 'ordre']);
            $table->dropColumn('ordre');
            $table->index(['est_publie', 'publie_le']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['est_publie', 'publie_le']);
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->index(['est_publie', 'ordre']);
        });
    }
};
