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
        Schema::table('actus', function (Blueprint $table) {
            $table->string('external_link')->nullable()->after('content');
            $table->enum('category', ['Actualités', 'Infos', 'Cultures', 'Rendez-vous', ''])->default('Actualités')->after('external_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actus', function (Blueprint $table) {
            $table->dropColumn('external_link');
            $table->dropColumn('category');
        });
    }
};
