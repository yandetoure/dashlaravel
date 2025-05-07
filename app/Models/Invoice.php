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
    ];

    // Ajoute ceci pour forcer le type float
    protected $casts = [
        'amount' => 'float',
    ];

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
