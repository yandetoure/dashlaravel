<?php declare(strict_types=1);


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'car_id', 'jour', 'heure', 'motif', 'diagnostique',
        'garagiste', 'prix', 'statut', 'note'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
