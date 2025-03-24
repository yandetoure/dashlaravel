<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = ['marque', 'model', 'year', 'matricule'];

    // Relation avec les chauffeurs via la table pivot
    public function drivers()
    {
        return $this->belongsToMany(User::class, 'car_drivers', 'car_id', 'chauffeur_id');
    }
    
    
    // Relation avec la table maintenance (une voiture peut avoir plusieurs maintenances)
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
