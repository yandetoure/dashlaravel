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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Couleur hex pour l'affichage
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insérer les catégories existantes
        DB::table('categories')->insert([
            ['name' => 'Actualités', 'description' => 'Actualités générales', 'color' => '#3B82F6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Infos utiles', 'description' => 'Informations pratiques', 'color' => '#10B981', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cultures', 'description' => 'Actualités culturelles', 'color' => '#F59E0B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rendez-vous', 'description' => 'Rendez-vous et événements', 'color' => '#EF4444', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
