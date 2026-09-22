<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Les articles de l'elevage.
 *
 * Le site en ligne a une rubrique « Articles » qui affiche « Aucun article
 * disponible actuellement » : une rubrique vide est pire que pas de rubrique,
 * elle donne l'impression d'un site abandonne. Celle-ci est alimentee depuis
 * le back-office, et la page ne s'affiche que s'il y a quelque chose a lire.
 *
 * date_publication porte la date affichee et le tri. Elle est distincte de
 * created_at : on peut antidater un article ecrit apres coup, ou en programmer
 * un pour plus tard — tant qu'elle est dans le futur, l'article n'est pas
 * publie.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('categorie')->nullable();        // conseils | vie de l'elevage | sante
            $table->text('chapeau');                        // le resume, affiche en liste
            $table->longText('corps');                      // le texte, en Markdown
            $table->string('photo_principale')->nullable();
            $table->date('date_publication');
            $table->boolean('est_publie')->default(false);
            $table->timestamps();

            $table->index(['est_publie', 'date_publication']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
