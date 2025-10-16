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
    ];

    // Ajoute ceci pour forcer le type float
    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
        'invoice_date' => 'datetime',
        'transaction_data' => 'array',
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

}
