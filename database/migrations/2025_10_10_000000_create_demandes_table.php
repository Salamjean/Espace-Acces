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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('name_demandeur');
            $table->string('prenom_demandeur');
            $table->string('contact_demandeur');
            $table->string('email_demandeur');
            $table->string('fonction_demandeur')->nullable();
            $table->integer('nbre_perso');
            $table->date('date_visite');
            $table->date('date_fin_visite');
            $table->time('heure_visite');
            $table->time('heure_fin_visite')->nullable();
            $table->string('motif_visite');
            $table->text('description_detaille')->nullable();
            $table->enum('statut', ['en_attente','approuve','rejete','annule','termine'])->default('en_attente');
            $table->string('numero_demande')->unique();
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->text('motif_rejet')->nullable();
            $table->string('path_qr_code')->nullable();
            $table->string('code_acces')->nullable();
            $table->timestamp('expiration_code_acces')->nullable();
            $table->boolean('is_read')->default(false);
            $table->text('user_agent')->nullable();
            $table->json('documents_joints')->nullable(); 
            $table->foreignId('demandeur_id')->nullable()->constrained('demandeurs')->onDelete('set null');

            $table->string('type_intervention')->nullable();
            $table->string('marque_voiture')->nullable();
            $table->string('modele_voiture')->nullable();
            $table->string('immatriculation_voiture')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
