<?php declare(strict_types=1); 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->date('jour');
            $table->time('heure');
            $table->string('motif');
            $table->text('diagnostique')->nullable();
            $table->string('garagiste');
            $table->decimal('prix', 10, 2)->default(0);
            $table->boolean('statut')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
