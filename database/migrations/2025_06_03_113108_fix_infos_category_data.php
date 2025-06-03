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
        // Mettre à jour toutes les variantes de "infos" vers "Infos utiles"
        DB::table('actus')
            ->whereIn('category', ['infos', 'Infos', 'INFOS', 'infos utiles'])
            ->update(['category' => 'Infos utiles']);
        
        // S'assurer que l'enum accepte les bonnes valeurs
        DB::statement("ALTER TABLE actus MODIFY COLUMN category ENUM('Actualités', 'Infos utiles', 'Cultures', 'Rendez-vous') DEFAULT 'Actualités'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Permettre de revenir en arrière si nécessaire
        DB::statement("ALTER TABLE actus MODIFY COLUMN category ENUM('Actualités', 'Infos utiles', 'Cultures', 'Rendez-vous', 'infos') DEFAULT 'Actualités'");
    }
};
