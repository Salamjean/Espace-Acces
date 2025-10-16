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
        Schema::create('visites', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers la table personne_demandes
            $table->foreignId('personne_demande_id')->constrained('personne_demandes')->onDelete('cascade');
            
            // Clé étrangère vers la table agents (l'agent qui a scanné)
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            
            // Informations sur la pièce d'identité
            $table->string('numero_piece', 50);
            $table->enum('type_piece', ['CNI', 'PASSEPORT', 'PERMIS', 'CARTE_SEJOUR']);
            
            // Photos de la pièce d'identité
            $table->string('photo_recto')->nullable();
            $table->string('photo_verso')->nullable();
            
            // Dates d'entrée et de sortie
            $table->timestamp('date_entree')->useCurrent();
            $table->timestamp('date_sortie')->nullable();
            
            // Statut de la visite
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            
            // Observations éventuelles
            $table->text('observations')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Index pour les performances
            $table->index('personne_demande_id');
            $table->index('agent_id');
            $table->index('statut');
            $table->index('date_entree');
            $table->index(['statut', 'date_entree']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};