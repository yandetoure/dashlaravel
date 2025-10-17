<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'reservation_id',
        'amount',
        'status',
        'invoice_number',
        'invoice_date',
        'payment_method',
        'transaction_id',
        'payment_url',
        'paid_at',
        'transaction_data',
        'total_amount_paid',
        'fee_amount',
        'net_amount_received',
        'fee_rate',
        'payment_method_used',
    ];

    // Ajoute ceci pour forcer le type float
    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
        'invoice_date' => 'datetime',
        'transaction_data' => 'array',
        'total_amount_paid' => 'float',
        'fee_amount' => 'float',
        'net_amount_received' => 'float',
        'fee_rate' => 'float',
    ];

    /**
     * Formater le montant de manière sécurisée
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format((float) $this->amount, 0) . ' XOF';
    }

    /**
     * Obtenir le montant comme nombre
     */
    public function getNumericAmountAttribute(): float
    {
        return (float) $this->amount;
    }

    public static function generateInvoiceNumber()
    {
        return 'INV-' . strtoupper(Str::random(8));
    }
    // Relation avec la réservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Obtenir le montant des frais formaté
     */
    public function getFormattedFeeAmountAttribute(): string
    {
        return number_format((float) ($this->fee_amount ?? 0), 0) . ' XOF';
    }

    /**
     * Obtenir le montant net formaté
     */
    public function getFormattedNetAmountAttribute(): string
    {
        return number_format((float) ($this->net_amount_received ?? 0), 0) . ' XOF';
    }

    /**
     * Obtenir le taux de frais en pourcentage
     */
    public function getFeeRatePercentageAttribute(): string
    {
        $rate = (float) ($this->fee_rate ?? 0);
        return number_format($rate * 100, 2) . '%';
    }

    /**
     * Vérifier si les frais ont été calculés
     */
    public function hasFeesCalculated(): bool
    {
        return !is_null($this->fee_amount) && !is_null($this->net_amount_received);
    }

}
