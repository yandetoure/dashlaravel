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
            // Champs pour les frais de transaction
            $table->decimal('total_amount_paid', 10, 2)->nullable()->comment('Montant total payé par le client');
            $table->decimal('fee_amount', 10, 2)->nullable()->comment('Montant des frais de transaction');
            $table->decimal('net_amount_received', 10, 2)->nullable()->comment('Montant net reçu par le vendeur');
            $table->decimal('fee_rate', 5, 4)->nullable()->comment('Taux de frais appliqué (ex: 0.025 pour 2.5%)');
            $table->string('payment_method_used')->nullable()->comment('Méthode de paiement utilisée');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'total_amount_paid',
                'fee_amount', 
                'net_amount_received',
                'fee_rate',
                'payment_method_used'
            ]);
        });
    }
};