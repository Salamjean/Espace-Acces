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
        Schema::create('agent_pointages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->date('date_pointage');
            $table->timestamp('heure_arrivee')->nullable();
            $table->timestamp('heure_depart')->nullable();
            $table->enum('statut', ['present', 'absent', 'en_retard'])->default('present');
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index(['agent_id', 'date_pointage']);
            $table->unique(['agent_id', 'date_pointage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_pointages');
    }
};
