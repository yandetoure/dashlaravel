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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->enum('statut', ['en_attente', 'en_cours', 'terminee', 'annulee'])->default('en_attente');
            $table->enum('note', ['satisfait', 'neutre', 'decu'])->nullable();
            $table->text('commentaire_positif')->nullable()->comment('Commentaire si satisfait');
            $table->text('commentaire_negatif')->nullable()->comment('Commentaire si déçu');
            $table->timestamp('debut_course')->nullable();
            $table->timestamp('fin_course')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};