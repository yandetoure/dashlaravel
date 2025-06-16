<?php declare(strict_types=1);

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
        Schema::table('reservations', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère existante
            $table->dropForeign(['client_id']);
            // Modifier la colonne pour la rendre nullable
            $table->foreignId('client_id')->nullable()->change();
            // Recréer la contrainte de clé étrangère
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['client_id']);
            // Modifier la colonne pour la rendre non nullable
            $table->foreignId('client_id')->nullable(false)->change();
            // Recréer la contrainte de clé étrangère
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}; 