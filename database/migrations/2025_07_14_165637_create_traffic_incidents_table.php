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
        Schema::create('traffic_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_id')->unique(); // ID unique de l'incident TomTom
            $table->string('type'); // Type d'incident (accident, travaux, etc.)
            $table->string('severity'); // Gravité (minor, major, critical)
            $table->text('description'); // Description de l'incident
            $table->decimal('latitude', 10, 8); // Latitude
            $table->decimal('longitude', 11, 8); // Longitude
            $table->string('road_name')->nullable(); // Nom de la route
            $table->string('direction')->nullable(); // Direction affectée
            $table->timestamp('start_time')->nullable(); // Heure de début
            $table->timestamp('end_time')->nullable(); // Heure de fin estimée
            $table->boolean('is_active')->default(true); // Si l'incident est toujours actif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_incidents');
    }
};
