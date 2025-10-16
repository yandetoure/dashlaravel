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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->text('payment_url')->nullable()->after('transaction_id');
            $table->timestamp('paid_at')->nullable()->after('payment_url');
            $table->json('transaction_data')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'transaction_id', 
                'payment_url',
                'paid_at',
                'transaction_data'
            ]);
        });
    }
};
