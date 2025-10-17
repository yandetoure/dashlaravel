<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne ENUM pour inclure 'Payée'
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('En_attente', 'Confirmée', 'Annulée', 'Payée') DEFAULT 'En_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre l'ancien ENUM sans 'Payée'
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('En_attente', 'Confirmée', 'Annulée') DEFAULT 'En_attente'");
    }
};