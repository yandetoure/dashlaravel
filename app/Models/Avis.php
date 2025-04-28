<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = ['note', 'comment'];
    

    public function reservation()
{
    return $this->belongsTo(Reservation::class);
}

}
