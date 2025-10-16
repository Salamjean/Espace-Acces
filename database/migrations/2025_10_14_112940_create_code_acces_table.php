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
        Schema::create('code_acces', function (Blueprint $table) {
            $table->id();
            $table->string('nom_porte');
            $table->enum('type', ['entree', 'sortie']);
            $table->string('code_unique')->unique();
            $table->string('qr_code_path')->nullable(); // Chemin du fichier QR code
            $table->boolean('est_actif')->default(true);
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_acces');
    }
};
