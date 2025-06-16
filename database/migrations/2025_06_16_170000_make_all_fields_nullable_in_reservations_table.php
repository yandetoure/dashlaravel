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
            // Rendre tous les champs nullable (sauf id, created_at, updated_at)
            $table->foreignId('client_id')->nullable()->change();
            $table->foreignId('trip_id')->nullable()->change();
            $table->foreignId('cardriver_id')->nullable()->change();
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('adresse_rammassage')->nullable()->change();
            $table->date('date')->nullable()->change();
            $table->time('heure_ramassage')->nullable()->change();
            $table->time('heure_vol')->nullable()->change();
            $table->time('heure_convocation')->nullable()->change();
            $table->string('numero_vol')->nullable()->change();
            $table->integer('nb_personnes')->unsigned()->nullable()->change();
            $table->integer('nb_valises')->unsigned()->nullable()->change();
            $table->integer('nb_adresses')->unsigned()->nullable()->change();
            $table->integer('tarif')->nullable()->change();
            $table->enum('status', ['En_attente', 'Confirmée', 'Annulée'])->nullable()->change();
            $table->unsignedTinyInteger('note')->nullable()->change();
            $table->unsignedBigInteger('id_agent')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable(false)->change();
            $table->foreignId('trip_id')->nullable(false)->change();
            $table->foreignId('cardriver_id')->nullable(false)->change();
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('adresse_rammassage')->nullable(false)->change();
            $table->date('date')->nullable(false)->change();
            $table->time('heure_ramassage')->nullable(false)->change();
            $table->time('heure_vol')->nullable(false)->change();
            $table->time('heure_convocation')->nullable(false)->change();
            $table->string('numero_vol')->nullable(false)->change();
            $table->integer('nb_personnes')->unsigned()->nullable(false)->change();
            $table->integer('nb_valises')->unsigned()->nullable(false)->change();
            $table->integer('nb_adresses')->unsigned()->nullable(false)->change();
            $table->integer('tarif')->nullable(false)->change();
            $table->enum('status', ['En_attente', 'Confirmée', 'Annulée'])->nullable(false)->change();
            $table->unsignedTinyInteger('note')->nullable(false)->change();
            $table->unsignedBigInteger('id_agent')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
        });
    }
}; 