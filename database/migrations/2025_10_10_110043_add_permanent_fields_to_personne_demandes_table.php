<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personne_demandes', function (Blueprint $table) {
            $table->enum('type_visiteur', ['ponctuel', 'permanent'])->default('ponctuel');
            $table->boolean('est_permanent')->default(false);
            $table->string('photo_recto')->nullable();
            $table->string('photo_verso')->nullable();
            $table->string('numero_piece')->nullable();
            $table->enum('type_piece', ['CNI', 'PASSEPORT', 'PERMIS', 'CARTE_SEJOUR'])->nullable();
            $table->text('motif_acces_permanent')->nullable();
            $table->date('date_debut_permanent')->nullable();
            $table->date('date_fin_permanent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personne_demandes', function (Blueprint $table) {
              $table->dropColumn([
                'type_visiteur',
                'est_permanent',
                'photo_recto',
                'photo_verso',
                'numero_piece',
                'type_piece',
                'motif_acces_permanent',
                'date_debut_permanent',
                'date_fin_permanent'
            ]);
        });
    }
};
