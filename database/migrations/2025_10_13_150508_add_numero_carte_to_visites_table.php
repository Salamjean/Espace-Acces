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
        Schema::table('visites', function (Blueprint $table) {
            $table->string('numero_carte', 20)->nullable()->after('agent_id');
            $table->index('numero_carte'); // Index pour recherches rapides
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visites', function (Blueprint $table) {
             $table->dropColumn('numero_carte');
        });
    }
};
