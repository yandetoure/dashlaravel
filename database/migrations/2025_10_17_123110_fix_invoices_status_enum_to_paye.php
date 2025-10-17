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
        // D'abord, ajouter 'payé' à l'enum existant
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('payée', 'payé', 'en_attente', 'offert') DEFAULT 'en_attente'");
        
        // Ensuite, mettre à jour les données existantes de 'payée' vers 'payé'
        DB::table('invoices')->where('status', 'payée')->update(['status' => 'payé']);
        
        // Enfin, supprimer 'payée' de l'enum pour ne garder que 'payé'
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('payé', 'en_attente', 'offert') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les données à l'ancien format
        DB::table('invoices')->where('status', 'payé')->update(['status' => 'payée']);
        
        // Remettre l'ancien ENUM avec 'payée'
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('payée', 'en_attente', 'offert') DEFAULT 'en_attente'");
    }
};