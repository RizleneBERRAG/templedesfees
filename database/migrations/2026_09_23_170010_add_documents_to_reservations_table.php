<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ce qu'il faut pour ecrire un contrat et une facture.
 *
 * Une reservation ne se contentait que de ce qui sert a encaisser : un chaton,
 * une famille, un montant. Un contrat de reservation et une facture d'acompte
 * demandent davantage — l'adresse postale des deux parties, le prix convenu, la
 * date de depart prevue.
 *
 * Le prix est recopie depuis la fiche du chaton au moment de la reservation, et
 * non lu a chaque affichage : un tarif peut changer d'une portee a l'autre, un
 * contrat deja signe ne change pas.
 *
 * Le numero de facture est alloue a l'encaissement, jamais avant : une facture
 * numerotee qui n'a jamais ete payee laisse un trou dans la numerotation, et une
 * numerotation a trous est exactement ce qu'un controle ne veut pas voir.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // L'adresse de la famille : un contrat identifie ses parties.
            $table->string('adresse')->nullable()->after('telephone');
            $table->string('code_postal', 10)->nullable()->after('adresse');
            $table->string('ville')->nullable()->after('code_postal');

            // Le prix convenu, fige au moment de la reservation.
            $table->unsignedInteger('prix_centimes')->nullable()->after('ville');

            // Le depart prevu. Par defaut celui de la portee, modifiable ici :
            // c'est cette date-la qui est ecrite au contrat.
            $table->date('depart_prevu_le')->nullable()->after('expire_le');

            // La facture d'acompte, une fois emise.
            $table->string('facture_numero', 20)->nullable()->unique()->after('paye_le');
            $table->timestamp('facture_le')->nullable()->after('facture_numero');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropUnique(['facture_numero']);
            $table->dropColumn([
                'adresse', 'code_postal', 'ville', 'prix_centimes',
                'depart_prevu_le', 'facture_numero', 'facture_le',
            ]);
        });
    }
};
