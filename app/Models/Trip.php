<?php declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['departure', 'destination'];

    // Relation inverse avec Reservation
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
