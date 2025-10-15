<?php declare(strict_types=1); 

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'heure_ramassage',
        'heure_vol',
        'heure_convocation',
        'numero_vol',
        'nb_personnes',
        'nb_valises',
        'nb_adresses',
        'tarif',
        'status',
        'trip_id',
        'id_agent',
        'email',
        'first_name',
        'last_name',
        'client_id',
        'adresse_rammassage',
        'cardriver_id',
        'phone_number',
    ];


       // Tarif par personne et par valise
       protected $tarifBasePersonnes = 32500; // Tarif pour 1 à 3 personnes
       protected $tarifParPersonneSupplementaire = 5000; // Supplément par personne au-delà de 3
       protected $tarifParValiseSupplementaire = 5000; // Supplément par valise supplémentaire
       protected $tarifDepotSupplementaire = 2500; // Supplément par dépôt ou ramassage supplémentaire
       protected $tarifAccompagnant = 15000; // Supplément pour accompagnant
       protected $tarifEnfantGratuit = 0; // Enfant (-2 ans) gratuit
   
       // Calcul du tarif de la réservation
       public function calculerTarif()
       {
           $tarif = 0;
   
           // Tarif de base pour les 1 à 3 personnes
           if ($this->nb_personnes <= 3) {
               $tarif = $this->tarifBasePersonnes;
           } else {
               // Si plus de 3 personnes, on ajoute 5.000F par personne supplémentaire
               $tarif = $this->tarifBasePersonnes + ($this->nb_personnes - 3) * $this->tarifParPersonneSupplementaire;
           }
   
           // Calcul des valises supplémentaires (plus de 2 par personne)
           if ($this->nb_valises > $this->nb_personnes * 2) {
               $valisesSupplementaires = $this->nb_valises - ($this->nb_personnes * 2);
               $tarif += $valisesSupplementaires * $this->tarifParValiseSupplementaire;
           }
   
           // Calcul des dépôts/ramassages supplémentaires
           $tarif += $this->nb_depots * $this->tarifDepotSupplementaire;
   
           // Calcul des accompagnants
           $tarif += $this->nb_accompagnants * $this->tarifAccompagnant;
   
           // Les enfants (-2 ans) sont gratuits
           // (Aucune action nécessaire pour les enfants gratuits, ils n'ajoutent rien au tarif)
   
           return $tarif;
       }
   
       // Avant d'enregistrer la réservation, on calcule le tarif
       public static function boot()
       {
           parent::boot();
   
           static::saving(function ($reservation) {
               $reservation->tarif = $reservation->calculerTarif();
           });
       }
       

    /**
     * Get the car associated with the chauffeur.
     */
    public function carDriver()
    {
        return $this->belongsTo(CarDriver::class, 'cardriver_id');
    }    

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }
    
    public function chauffeur() {
        return $this->hasOneThrough(
            User::class,
            CarDriver::class,
            'id',           // clé primaire dans CarDriver
            'id',           // clé primaire dans User
            'cardriver_id', // clé étrangère dans Reservation
            'chauffeur_id'  // clé étrangère dans CarDriver
        );
    }
    
    public function entreprise() {
        return $this->belongsTo(User::class, 'entreprise_id');
    }
    
    public function agent() {
        return $this->belongsTo(User::class, 'id_agent');
    }
    
    public function car()
    {
    // Relation à travers la table pivot car_drivers
    return $this->hasOneThrough(
        Car::class, 
        CarDriver::class, 
        'chauffeur_id', // clé étrangère dans la table CarDriver
        'id',            // clé primaire dans la table Car
        'cardriver_id',  // clé étrangère dans la table Reservation
        'car_id'         // clé primaire dans la table CarDriver
    );
}


    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function avis()
{
    return $this->hasOne(Avis::class);
}

public function invoice()
{
    return $this->hasOne(Invoice::class);
}

public function course()
{
    return $this->hasOne(Course::class);
}


}
