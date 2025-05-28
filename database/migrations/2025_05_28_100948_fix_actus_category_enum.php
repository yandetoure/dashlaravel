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
        // Sauvegarder les données existantes
        $actus = DB::table('actus')->get();
        
        // Supprimer la colonne category existante
        Schema::table('actus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
        
        // Recréer la colonne category avec les bonnes valeurs
        Schema::table('actus', function (Blueprint $table) {
            $table->enum('category', ['Actualités', 'Infos utiles', 'Cultures', 'Rendez-vous'])->default('Actualités')->after('external_link');
        });
        
        // Restaurer les données en mappant les anciennes valeurs
        foreach ($actus as $actu) {
            $category = $actu->category ?? 'Actualités';
            
            // Mapper les anciennes valeurs vers les nouvelles
            if ($category === 'Infos') {
                $category = 'Infos utiles';
            }
            
            DB::table('actus')
                ->where('id', $actu->id)
                ->update(['category' => $category]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sauvegarder les données existantes
        $actus = DB::table('actus')->get();
        
        // Supprimer la colonne category
        Schema::table('actus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
        
        // Recréer avec les anciennes valeurs
        Schema::table('actus', function (Blueprint $table) {
            $table->enum('category', ['Actualités', 'Infos', 'Cultures', 'Rendez-vous', ''])->default('Actualités')->after('external_link');
        });
        
        // Restaurer les données en mappant vers les anciennes valeurs
        foreach ($actus as $actu) {
            $category = $actu->category ?? 'Actualités';
            
            // Mapper les nouvelles valeurs vers les anciennes
            if ($category === 'Infos utiles') {
                $category = 'Infos';
            }
            
            DB::table('actus')
                ->where('id', $actu->id)
                ->update(['category' => $category]);
        }
    }
};
