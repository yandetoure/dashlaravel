<?php declare(strict_types=1); 

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('driver_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->unsignedBigInteger('driver_1_id')->nullable();
            $table->unsignedBigInteger('driver_2_id')->nullable();
            $table->unsignedBigInteger('driver_3_id')->nullable();
            $table->unsignedBigInteger('driver_4_id')->nullable();
            $table->integer('current_rotation_day')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('driver_1_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('driver_2_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('driver_3_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('driver_4_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_groups');
    }
}; 