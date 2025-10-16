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
        Schema::create('agent_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->foreignId('code_acces_id')->constrained('code_acces')->onDelete('cascade');
            $table->string('type_scan'); // entree ou sortie
            $table->string('nom_porte');
            $table->string('code_unique_scanne');
            $table->timestamp('heure_scan');
            $table->boolean('est_valide')->default(true);
            $table->text('raison_invalidite')->nullable();
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['agent_id', 'heure_scan']);
            $table->index(['code_unique_scanne', 'heure_scan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_scans');
    }
};
