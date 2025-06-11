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
        // Sauvegarder les données existantes avec mapping vers les IDs
        $actus = DB::table('actus')->get();
        $categoryMappings = [
            'Actualités' => 1,
            'Infos utiles' => 2,
            'Cultures' => 3,
            'Rendez-vous' => 4,
        ];

        // Ajouter temporairement une colonne category_id
        Schema::table('actus', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('external_link');
        });

        // Mapper les anciennes valeurs enum vers les IDs des catégories
        foreach ($actus as $actu) {
            $categoryName = $actu->category ?? 'Actualités';
            $categoryId = $categoryMappings[$categoryName] ?? 1;
            
            DB::table('actus')
                ->where('id', $actu->id)
                ->update(['category_id' => $categoryId]);
        }

        // Supprimer l'ancienne colonne enum et rendre category_id non-nullable
        Schema::table('actus', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Récupérer les données pour reconstituer l'enum
        $actus = DB::table('actus')
            ->join('categories', 'actus.category_id', '=', 'categories.id')
            ->select('actus.id', 'categories.name as category_name')
            ->get();

        // Supprimer la foreign key et la colonne category_id
        Schema::table('actus', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Remettre la colonne enum
        Schema::table('actus', function (Blueprint $table) {
            $table->enum('category', ['Actualités', 'Infos utiles', 'Cultures', 'Rendez-vous'])->default('Actualités')->after('external_link');
        });

        // Restaurer les données
        foreach ($actus as $actu) {
            DB::table('actus')
                ->where('id', $actu->id)
                ->update(['category' => $actu->category_name]);
        }
    }
};
