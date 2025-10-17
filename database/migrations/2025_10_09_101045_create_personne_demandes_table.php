<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personne_demandes', function (Blueprint $table) {
            $table->id();
            
            // Informations personnelles
            $table->string('name');
            $table->string('prenom');
            $table->string('contact');
            $table->string('email');
            $table->string('password')->default(Hash::make('default'));
            $table->string('adresse');
            $table->string('fonction');
            $table->string('structure');
            $table->string('profile_picture')->nullable();
            
            // Informations de la demande (spécifiques à chaque personne)
            $table->date('date_visite');
            $table->date('date_fin_visite');
            $table->time('heure_visite');
            $table->time('heure_fin_visite')->nullable();
            $table->string('motif_visite');
            $table->text('description_detaille')->nullable();
            $table->integer('nbre_perso'); // Nombre total de personnes dans le groupe
            
            // Informations véhicule (spécifiques à chaque personne)
            $table->string('marque_voiture')->nullable();
            $table->string('modele_voiture')->nullable();
            $table->string('immatriculation_voiture')->nullable();
            $table->string('type_intervention')->nullable();
            
            // Gestion de la demande
            $table->enum('statut', ['en_attente','approuve','rejete','annule','termine'])->default('en_attente');
            $table->string('numero_demande'); // Numéro commun pour toutes les personnes du même groupe
            $table->foreignId('demandeur_id')->nullable()->constrained('demandeurs')->onDelete('set null');
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->text('motif_rejet')->nullable();
            $table->string('path_qr_code')->nullable();
            $table->string('code_acces')->nullable();
            $table->timestamp('expiration_code_acces')->nullable();
            $table->boolean('is_read')->default(false);
            $table->text('user_agent')->nullable();
            $table->json('documents_joints')->nullable();
            
            // Pour gérer le groupe de personnes
            $table->string('groupe_id'); // Identifiant unique pour regrouper les personnes d'une même demande
            $table->boolean('est_demandeur_principal')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personne_demandes');
    }
};
