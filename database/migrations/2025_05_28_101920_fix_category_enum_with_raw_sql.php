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
        // Utiliser du SQL brut pour modifier l'enum
        DB::statement("ALTER TABLE actus MODIFY COLUMN category ENUM('Actualités', 'Infos utiles', 'Cultures', 'Rendez-vous') DEFAULT 'Actualités'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancien enum
        DB::statement("ALTER TABLE actus MODIFY COLUMN category ENUM('Actualités', 'Infos', 'Cultures', 'Rendez-vous', '') DEFAULT 'Actualités'");
    }
};
