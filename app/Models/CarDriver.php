<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDriver extends Model
{
    protected $table = 'car_drivers';

    protected $fillable = ['car_id', 'chauffeur_id'];

    public $timestamps = true;


    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'cardriver_id');
    }

}
