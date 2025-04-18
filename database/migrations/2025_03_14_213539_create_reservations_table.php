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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cardriver_id')->constrained('car_drivers')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->date('date');
            $table->time('heure_ramassage');
            $table->string('adresse_rammassage');
            $table->time('heure_vol')->nullable();
            $table->time('heure_convocation')->nullable();
            $table->string('numero_vol');
            $table->integer('nb_personnes')->unsigned();
            $table->integer('nb_valises')->unsigned();
            $table->integer('nb_adresses')->unsigned()->nullable();
            $table->integer('tarif');
            $table->enum('status', ['En_attente', 'Confirmée', 'Annulée'])->default('En_attente');
            $table->unsignedBigInteger('id_agent')->nullable();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('users');     }
};
