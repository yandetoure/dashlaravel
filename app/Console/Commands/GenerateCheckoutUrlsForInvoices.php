<?php declare(strict_types=1); 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Services\NabooPayService;

class GenerateCheckoutUrlsForInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-checkout-urls {--force : Force la régénération même si l\'URL existe déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les URLs de checkout NabooPay pour toutes les factures en attente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Génération des URLs de checkout NabooPay...');
        
        $force = $this->option('force');
        
        // Récupérer les factures en attente
        $query = Invoice::where('status', 'en_attente')
                       ->whereHas('reservation');
        
        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('payment_url')
                  ->orWhere('payment_url', '');
            });
        }
        
        $invoices = $query->get();
        
        if ($invoices->isEmpty()) {
            $this->info('✅ Aucune facture nécessitant une URL de checkout.');
            return;
        }
        
        $this->info("📋 {$invoices->count()} facture(s) à traiter...");
        
        $nabooPayService = app(NabooPayService::class);
        $successCount = 0;
        $errorCount = 0;
        
        $progressBar = $this->output->createProgressBar($invoices->count());
        $progressBar->start();
        
        foreach ($invoices as $invoice) {
            try {
                if (!$invoice->reservation) {
                    $this->error("❌ Facture {$invoice->id}: Réservation manquante");
                    $errorCount++;
                    $progressBar->advance();
                    continue;
                }
                
                $result = $nabooPayService->createReservationTransaction($invoice->reservation);
                
                if (isset($result['checkout_url'])) {
                    $invoice->update([
                        'payment_url' => $result['checkout_url'],
                        'transaction_id' => $result['transaction_id'] ?? null
                    ]);
                    
                    $successCount++;
                } else {
                    $this->error("❌ Facture {$invoice->id}: Impossible de générer l'URL");
                    $errorCount++;
                }
                
            } catch (\Exception $e) {
                $this->error("❌ Facture {$invoice->id}: {$e->getMessage()}");
                $errorCount++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        // Résumé
        $this->info("📊 Résumé:");
        $this->info("✅ Succès: {$successCount}");
        $this->info("❌ Erreurs: {$errorCount}");
        
        if ($successCount > 0) {
            $this->info("🎉 URLs de checkout générées avec succès!");
        }
        
        if ($errorCount > 0) {
            $this->warn("⚠️  Certaines factures n'ont pas pu être traitées. Vérifiez les logs.");
        }
    }
}